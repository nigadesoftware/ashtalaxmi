'
' Created by SharpDevelop.
' User: admin
' Date: 08/01/2019
' Time: 15:47
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Partial Class farmerfortnightsms
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	Function SendSMS(mobile As String,message As String) As Integer
		Try
			Dim sms1 As New smslib.smsozone
			Dim ret As String
			Dim str As String 
			sms1.username="kadwasakar"
			sms1.password="kadwa@12001"
			sms1.mobile=mobile
			sms1.sender="KDWSSK"
			sms1.channel="trans"
			sms1.dcs="8"
			sms1.route="1055"
			sms1.flashsms="0"
			sms1.url = "http://smsozone.com/api/mt/SendSMS?"
			sms1.message = message.Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()
			ret = sms1.sendSMS
			str= Mid(ret,15,3)
			If str="000" then
				Return 1
			Else
				Return -10	
			End if
		Catch ae As Exception
			richTextBox1.SelectionColor = Color.Red
			richTextBox1.AppendText("Send SMS" + ae.Message().ToString()+ vbNewLine)
		End Try
	End function
	Sub updatesmsstatus(yearcode As Integer, billno As Long,status As Integer)
		Try 
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			 Dim sql As String 
			cmd1.CommandType = CommandType.Text	
			sql="update farmerbillheader set issmssent = " + status.ToString() + " where transactionnumber=" + billno.ToString()
			cmd1.CommandText = sql
			cmd1.ExecuteNonQuery()
			'richTextBox1.AppendText("Weightslip is updated"+ vbNewLine)
			'richTextBox1.Refresh
			Catch ae As Exception
			richTextBox1.SelectionColor = Color.Red
			richTextBox1.AppendText("Update Status " + ae.Message().ToString()+ vbNewLine)
		End Try
	End Sub
	Sub updatefortnightstatus(yearcode As Integer, billperiodtransnumber As Long,status As Integer)
		Try 
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			cmd1.CommandType = CommandType.Text	
			cmd1.CommandText = "update billperiodheader set issmssent = " + status.ToString() & _
			" where seasonyear ='"+ yearcode.ToString() + "'" & _
			" and billperiodtransnumber=" + billperiodtransnumber.ToString()
			
			cmd1.ExecuteNonQuery()
			richTextBox1.AppendText("SMS of Fortnight Completed"+ vbNewLine)
			richTextBox1.Refresh
			Catch ae As Exception
			richTextBox1.SelectionColor = Color.Red
			richTextBox1.AppendText("Update Status " + ae.Message().ToString()+ vbNewLine)
		End Try
	End Sub
	Sub getnewbill()
		Try 
			Dim sql As String = "Select b.seasonyear vyear_code,b.billcategorycode nbill_type,b.billperiodnumber nfortnight_no,w.transactionnumber nbill_no,'XXXXXX'||to_char(Mod(f.mobilenumber,10000)) As mobile_no, " & _
                "get_farmer_payment_message(b.seasonyear,w.transactionnumber) As msg,f.mobilenumber nmobile_no " & _
                "from farmerbillheader w,farmer f,billperiodheader b " & _
                "where w.farmercode=f.farmercode " & _
                "and b.billperiodtransnumber= " + billperiodno.ToString & _
                " and f.mobilenumber>1000000 " & _
                "And nvl(w.issmssent,0)<>1 " & _
                "And nvl(b.issmssent,0)<>1 " & _
                "order by w.transactionnumber "
			Dim cmd As New OracleCommand(sql, conn)
			Dim ret As Integer
			Dim year_code As String 
			Dim bill_type As Integer
			Dim fortnight_no As Integer
			Dim billno As Long 
			cmd.CommandType = CommandType.Text
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			Dim sb = new System.Text.StringBuilder()
    		sb.Append("Message is Sent Successfully")
    		Dim sbn = new System.Text.StringBuilder()
    		sbn.Append("Message is Not Sent")
    		Using dr
    			While dr.Read()
    				year_code = dr("vyear_code")
    				bill_type = dr("nbill_type")
    				fortnight_no = dr("nfortnight_no")
    				billno=dr("nbill_no")
					ret = SendSMS(dr("nmobile_no").ToString(),dr("msg"))
					If (ret=1) Then
						updatesmsstatus(dr("vyear_code"),dr("nbill_no"),1)
						richTextBox1.SelectionColor = Color.Black
						richTextBox1.AppendText(dr("msg").Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()+ vbNewLine)
						richTextBox1.SelectionColor = Color.Green
						richTextBox1.AppendText("Message sent successfully")
						'richTextBox1.AppendText(sb.ToString() + " on Mobile No-" + dr("mobile_no").ToString()+" "+DateTime.now.ToString("dd/MM/yyyy h:mm:ss tt") + vbNewLine)
					Else
						updatesmsstatus(dr("vyear_code"),dr("nbill_no"),0)
						richTextBox1.SelectionColor = Color.Black
						richTextBox1.AppendText(dr("msg").ToString()+ vbNewLine)
						richTextBox1.SelectionColor = Color.Red
						richTextBox1.AppendText(sbn.ToString() + " " +DateTime.now.ToString("dd/MM/yyyy h:mm:ss tt") + vbNewLine)
					End If
					richTextBox1.Refresh
    			End While
    			updatefortnightstatus(year_code,billperiodno,1)
			End Using
			Catch ae As Exception
				richTextBox1.SelectionColor = Color.Red
				richTextBox1.AppendText("Next SMS" + ae.Message().ToString()+ vbNewLine)
		End Try
	End Sub
	
	Sub farmerfortnightLoad(sender As Object, e As EventArgs)

	End Sub
	
	Sub farmerfortnightShown(sender As Object, e As EventArgs)
		If billperiodno>0 then
			getnewbill()
		End if
	End Sub
	
	Sub BtnExitClick(sender As Object, e As EventArgs)
		Me.Close()	
	End Sub
	
	Sub Button1Click(sender As Object, e As EventArgs)
	End Sub
End Class