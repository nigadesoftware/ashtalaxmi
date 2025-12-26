'
' Created by SharpDevelop.
' User: admin
' Date: 04/06/2019
' Time: 19:19
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports System
Imports System.IO.Ports
'Imports Oracle.DataAccess.Client
Imports System.Data
'Imports MySql.Data.MySqlClient
'Imports System
Imports System.Data.OracleClient
Imports System.Net.NetworkInformation
Public Class weighbridge
    Shared _serialPort As SerialPort
    Shared _rawdata As String
    Shared _conn As OracleConnection
    Public weight As Decimal
    Public katacode As Integer
    Public iserror as Boolean
    Public isopened As Boolean
    Public macaddress As String
    Public Sub New(p_conn As OracleConnection)
    	' Initialize without a course
    	Dim mac As String
	_conn = p_conn    	
    	_serialPort = New SerialPort()
    	macaddress=GetMacAddress()
    	getkatacode()
    End Sub
    Function getkatacode()
    		Dim sql As String
		Dim dr As OracleDataReader
		sql = "select * from kata where macaddress='" & macaddress & "'"
            Dim cmd As New OracleCommand(sql,_conn)
            cmd.CommandType = Data.CommandType.Text
            dr = cmd.ExecuteReader
            If dr.HasRows = True Then
                dr.Read()
                If IsDBNull(dr.Item("KATACODE")) Then
                   katacode =0	
                Else
                   katacode =dr.Item("KATACODE")
                End If
            End If
            dr.Close
    End Function
    function setkataparameter() As Boolean
    	
    		Dim sql As String
		Dim dr As OracleDataReader
		sql = "select * from kata where katacode=" & Val(katacode)
            Dim cmd As New OracleCommand(sql,_conn)
            cmd.CommandType = Data.CommandType.Text
            dr = cmd.ExecuteReader
            If dr.HasRows = True Then
                dr.Read()
                If IsDBNull(dr.Item("PORT")) Then
                    _serialPort.PortName = "COM1"
                Else
                    _serialPort.PortName = "COM" & dr.Item("PORT")
                End If

                If IsDBNull(dr.Item("BAUDRATE")) Then
                    _serialPort.BaudRate = 9600
                Else
                    _serialPort.BaudRate = Val(dr.Item("BAUDRATE"))
                End If

                If IsDBNull(dr.Item("DATABIT")) Then
                    _serialPort.DataBits = 8
                Else
                    _serialPort.DataBits = Val(dr.Item("DATABIT"))
                End If

                If IsDBNull(dr.Item("STOPBIT")) Then
                    _serialPort.StopBits = ""
                Else
                    _serialPort.StopBits = Val(dr.Item("STOPBIT"))
                End If

                If IsDBNull(dr.Item("PARITY")) Then
                    _serialPort.Parity = IO.Ports.Parity.Even
                Else
                	 If dr.Item("PARITY") = "1" Then
                        _serialPort.Parity = IO.Ports.Parity.Odd
                	 ElseIf dr.Item("PARITY") = "2" Then
                        _serialPort.Parity = IO.Ports.Parity.Even
                   ElseIf dr.Item("PARITY") = "3" Then
                        _serialPort.Parity = IO.Ports.Parity.None
                   End If

                End If
                If IsDBNull(dr.Item("STOPBIT")) Then
                    _serialPort.ReceivedBytesThreshold = 30
                Else
                    _serialPort.ReceivedBytesThreshold =  Val(dr.Item("BUFFERSIZE"))
                End If
                dr.Close
                Return False
            Else
                Return True	
		End If
    End Function
    Public Sub open()
            Try
            	If _serialPort.IsOpen = False then
            		_serialPort.Open
            	End if
            	isopened=_serialPort.IsOpen
            Catch __unusedTimeoutException1__ As TimeoutException
            End Try
    End Sub
    function checkrawdataallowed() As Boolean
    	
    		Dim sql As String
		Dim dr As OracleDataReader
            sql = "select * from kata where katacode=" & Val(katacode)
            Dim cmd As New OracleCommand(sql,_conn)
            cmd.CommandType = Data.CommandType.Text
            dr = cmd.ExecuteReader
            If dr.HasRows = True Then
                dr.Read()
                If IsDBNull(dr.Item("ISALLOWED")) Then
                	    dr.Close	
                	    Return False
	          ElseIf (dr.Item("ISALLOWED")=1) Then
	          	    dr.Close
	          	    Return True
	          Else
	          	    dr.Close
	          	    Return False
	          End If
            End If
            dr.Close
    End Function
    
    Function updateweight()
    		Dim sql As String
            sql = "update kata set weight = " & weight &" where katacode=" & Val(katacode)
            Dim cmd As New OracleCommand(sql,_conn)
            cmd.CommandType = Data.CommandType.Text
            cmd.ExecuteNonQuery()
    End Function
    Public Sub Read()
            Try
            	_rawdata = _serialPort.ReadExisting()
            	dataextraction()
            	'weight =  Math.Ceiling( Rnd() * 25 )/2
            	weight=  Format(Val(aRand), "00.000")
            Catch __unusedTimeoutException1__ As TimeoutException
            End Try
    End Sub
    Dim prng As New Random
    Private Function aRand() As Double
    	Return Math.Round(prng.Next(0, 2601) / 100, 3)
    End Function
    Public Sub dataextraction()

        Dim a, b, c,tmp As String
        Select Case katacode
            'Case 1, 2
            '    tmp = Chr(3) & Chr(2)
            '    a = InStr(rawdata, "+", vbTextCompare)
            '    b = InStr(a + 1, rawdata, tmp, vbTextCompare)

            '    If a = 0 Or b = 0 Then
            '        If Val(a) > 0 And b = 0 Then
            '            txtloadweight.Text = Format(Val(rawdata) / 100000, "00.000")
            '        End If
            '    Else
            '        c = Mid(rawdata, a + 1, b - c - 1)
            '        txtloadweight.Text = Format(Val(c) / 100000, "00.000")
            '    End If

            Case 1, 2, 3, 4, 5

                tmp = Chr(3) & Chr(2)
                a = InStr(_rawdata, Chr(13) & Chr(10), vbTextCompare)
                b = InStr(a + 1, _rawdata, Chr(13) & Chr(10), vbTextCompare)

                If a = 0 Or b = 0 Then
                    If Val(a) > 0 And b = 0 Then
                        c = Mid(_rawdata, a + 4, 6)
4:
                        weight = Format(Val(c) / 1000, "00.000")
                    End If
                Else
                    c = Mid(_rawdata, a + 4, 6)
                    weight = Format(Val(c) / 1000, "00.000")
                End If
            Case 6

                tmp = Chr(13) & Chr(10)
                a = InStr(_rawdata, "kg", vbTextCompare)
                b = InStr(a + 1, _rawdata, "kg", vbTextCompare)

                If a = 0 Or b = 0 Then
                    'If Val(a) > 0 And b = 0 Then
                    '    c = Mid(rawdata, a + 1, 6)
                    '    c = c + "0"
                    '    txtloadweight.Text = Format(Val(c) / 100000, "00.000")
                    'End If
                Else
                    c = Mid(_rawdata, a + 20, 7)
                    'c = c + "0"
                    weight = Format(Val(c) / 1000, "00.000")
                End If
        End Select
        ' txtloadweight.Text=  = Format(Val(rawdata) / 1000, "0.000")
    End Sub
    Private Function GetMacAddress() As String
	    Dim macAddresses As String = String.Empty
	
	    For Each nic As NetworkInterface In NetworkInterface.GetAllNetworkInterfaces()
	
	        If nic.OperationalStatus = OperationalStatus.Up Then
	            macAddresses += nic.GetPhysicalAddress().ToString()
	            Exit For
	        End If
	    Next
	    Return macAddresses
    End Function
End Class
