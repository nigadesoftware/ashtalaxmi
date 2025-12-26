'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 11:51
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Partial Class MainForm
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	Sub updateunicodedata(tablename As String,keyfield As String,fieldname_isf As String, fieldname_uni As String,cond As String)
		Try 
        	Dim sql As String = "select "+ keyfield +","+ fieldname_isf +" from "+ tablename +" where "+ fieldname_isf +" is not null and "+ fieldname_uni +" is null "+ If(cond<>""," and " + cond,"")+" order by "+ keyfield
			StartConnection()
			Dim cmd As New OracleCommand(sql, conn)
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			cmd.CommandType = CommandType.Text
			cmd1.CommandType = CommandType.Text	
			
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblCode.Text = dr(keyfield).ToString()
				lblNameUni.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr(fieldname_isf).ToString(),"DVB"))
				cmd1.CommandText = "update "+ tablename +" set "+ fieldname_uni +" = '" & lblNameUni.Text & "' where "+ keyfield +" = " & dr(keyfield)
				cmd1.ExecuteNonQuery()
				lblCode.Refresh
				lblNameUni.Refresh
			End While
			StopConnection()
			lblCode.Text = ""
			lblCode.Refresh
			lblNameUni.Text=tablename+" is updated"
			lblNameUni.Refresh
			Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Sub
	
	Sub BtnConvertClick(sender As Object, e As EventArgs)
		updateunicodedata("ac_voucher_header","ntrans_no","vrec_from","vrec_from_uni","vfin_year='2019-2020'")	
	End Sub
	
	Sub BtnVoucherHeaderClick(sender As Object, e As EventArgs)
		Dim voucherheader1 As New voucherheader
		voucherheader1.Show() 
	End Sub
End Class
