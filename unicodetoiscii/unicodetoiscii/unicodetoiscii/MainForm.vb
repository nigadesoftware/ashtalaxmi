'
' Created by SharpDevelop.
' User: admin
' Date: 12/5/2019
' Time: 6:20 PM
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports System.Data
Imports System.Data.OracleClient
Public Partial Class MainForm
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Sub Button1Click(sender As Object, e As EventArgs)
			Try 
        	Dim sql As String = "select * from astoria_farmer_master order by nfarmer_code"
			StartConnection()
			Dim cmd As New OracleCommand(sql, conn)
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			Dim str_iscii As String
			Dim str_isfoc As String
			cmd.CommandType = CommandType.Text
			cmd1.CommandType = CommandType.Text	
			
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblCode.Text = dr("nfarmer_code").ToString()
				lblNameUni.Text = dr("vfarmer_name_uni")
				str_iscii =	Trim(str.UNICODETOISCII(dr("vfarmer_name_uni")))
				str_isfoc = Trim(str.IsciiToIsfoc(str_iscii,"DVBW"))
				cmd1.CommandText = "update astoria_farmer_master set  vfarmer_name_iscii = '" +str_iscii+"', vfarmer_name_isfoc = '" +str_isfoc+"' where nfarmer_code = " & dr("nfarmer_code")
				cmd1.ExecuteNonQuery()
				lblCode.Refresh
				lblNameUni.Refresh
			End While
			StopConnection()
			lblCode.Text = ""
			lblCode.Refresh
			lblNameUni.Text="Farmer is updated"
			lblNameUni.Refresh
			Catch ae As Exception
			MsgBox(ae.Message())
		End Try	
	End Sub
End Class
