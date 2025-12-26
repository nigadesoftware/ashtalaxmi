'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 18:50
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Module dataconversionmodule
	Public oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.254)(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=orclweb)));" _
           + "User Id=nst_nasaka_finance;Password=swapp123;"
    Public conn As OracleConnection = New OracleConnection(oradb)
    Public billperiodno As Integer
    Public qr As String
    Public Sub StartConnection()
    	If conn.State <> ConnectionState.Open  Then
    		conn.Open
    	End If
    End Sub
    
    Public Sub StopConnection()
    	If conn.State = ConnectionState.Open  Then
    		conn.Close
    	End If
    End Sub
    
End Module
