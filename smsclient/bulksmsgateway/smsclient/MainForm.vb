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
Public Partial Class MainForm
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	Sub Button1Click(sender As Object, e As EventArgs)
'		Dim sms1 As New smslib.SMS
'		Dim ret As String
'		sms1.username="kadwasugar"
'		sms1.password="kadwa@123"
'		'sms1.mobile="9822617847"
'		sms1.mobile="7276249056"
'		sms1.sender="KDWSSK"
'		sms1.type="3"
'		sms1.url = "https://login.bulksmsgateway.in/unicodesmsapi.php?"
'		sms1.message = "नाशिक ससाका कोड-14521 सौ.पाटील प्रतिभा भगिरथ यांची ऊस वजन पावती-1 दि-15/01/2011 गाव-पिंपळनारे निव्वळ वजन-11.108 मे.टन".Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()
'		sms1.istest=True
'		sms1.istransportverbose=False
'		'sms1.message = "Sandeep Nigade Test Message"
'		ret = sms1.sendSMS
'		lblNameUni.Text = ret
		'updatesmsstatus("192.168.1.254","orclweb","nst_nasaka_webpub","swapp123","com_weighbridge_master","issmssent")
		'getnewslip("2010-2011")
		'SendSMS("9822617847","नाशिक ससाका कोड-14521 सौ.पाटील प्रतिभा भगिरथ यांची ऊस वजन पावती-1 दि-15/01/2011 गाव-पिंपळनारे निव्वळ वजन-11.108 मे.टन")
	End Sub
	Function SendSMS(mobile As String,message As String) As Integer
		Try
			Dim sms1 As New smslib.SMS
			Dim ret As String
			sms1.username="kadwasugar"
			sms1.password="kadwa@123"
			'sms1.mobile="9822617847"
			'sms1.mobile="7276249056"
			sms1.mobile = mobile
			sms1.sender="KDWSSK"
			sms1.type="3"
			sms1.url = "https://login.bulksmsgateway.in/unicodesmsapi.php?"
			'sms1.message = "नाशिक ससाका कोड-14521 सौ.पाटील प्रतिभा भगिरथ यांची ऊस वजन पावती-1 दि-15/01/2011 गाव-पिंपळनारे निव्वळ वजन-11.108 मे.टन".Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()
			sms1.message = message.Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()
			sms1.istest=False
			sms1.istransportverbose=True
			'sms1.message = "Sandeep Nigade Test Message"
			ret = sms1.sendSMS
			Return 1
		Catch
			Catch ae As Exception
			richTextBox1.SelectionColor = Color.Red
			richTextBox1.AppendText("Send SMS" + ae.Message().ToString()+ vbNewLine)
			Timer1.Enabled=True
		End Try
	End function
	Sub updatesmsstatus(yearcode As String, slipno As Integer,status As Integer)
		Try 
'        	Dim oradb As String = "Data Source=(DESCRIPTION=" _
'           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST="+ orahost +")(PORT=1521)))" _
'           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME="+ oraservice +")));" _
'           + "User Id="+ dbuser +";Password="+ dbpwd +";"
'        	Dim conn As New OracleConnection(oradb)
'			conn.Open()
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			cmd1.CommandType = CommandType.Text	
			cmd1.CommandText = "update com_weight_slip set issmssent = " + status.ToString() + " where vyear_code ='"+ yearcode + "' and nslip_no=" + slipno.ToString()
			cmd1.ExecuteNonQuery()
			'richTextBox1.AppendText("Weightslip is updated"+ vbNewLine)
			'richTextBox1.Refresh
			Catch ae As Exception
			richTextBox1.SelectionColor = Color.Red
			richTextBox1.AppendText("Update Status " + ae.Message().ToString()+ vbNewLine)
			Timer1.Enabled=True
		End Try
	End Sub
	Sub getnewslip(yearcode As String)
		Try 
'        	Dim oradb As String = "Data Source=(DESCRIPTION=" _
'           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST="+ orahost +")(PORT=1521)))" _
'           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME="+ oraservice +")));" _
'           + "User Id="+ dbuser +";Password="+ dbpwd +";"
'        	Dim conn As New OracleConnection(oradb)
'			conn.Open()

			'Dim sql As String = "select vyear_code,nslip_no,'XXXXXX'||to_char(mod(f.nmobile_no,10000)) as mobile_no,get_message(vyear_code,nslip_no) as msg,f.nmobile_no,w.vout_time from com_weight_slip w,com_farmer_master@sugarerplink f where w.nfarmer_code=f.nfarmer_code and f.nmobile_no>1000000 and  vyear_code='" + yearcode + "' and nnet_weight>0 and f.nfarmer_code in (7559) order by vout_time"
			Dim sql As String = "select vyear_code,nslip_no,'XXXXXX'||to_char(mod(f.nmobile_no,10000)) as mobile_no,get_message(vyear_code,nslip_no) as msg,f.nmobile_no,w.vout_time from com_weight_slip w,com_farmer_master@sugarerplink f where w.nfarmer_code=f.nfarmer_code and f.nmobile_no>1000000 and  vyear_code='" + yearcode + "' and nnet_weight>0 and nvl(issmssent,0)<>1 order by vout_time"
			Dim cmd As New OracleCommand(sql, conn)
			Dim ret As Integer
			cmd.CommandType = CommandType.Text
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			Dim sb = new System.Text.StringBuilder()
    		sb.Append("Message is Sent Successfully")
    		Dim sbn = new System.Text.StringBuilder()
    		sbn.Append("Message is Not Sent")
    		Using dr
				While dr.Read()
					ret = SendSMS(dr("nmobile_no").ToString(),dr("msg"))
					If (ret=1) Then
						updatesmsstatus(dr("vyear_code"),dr("nslip_no"),1)
						richTextBox1.SelectionColor = Color.Black
						richTextBox1.AppendText(dr("msg").Replace("1","१").Replace("2","२").Replace("3","३").Replace("4","४").Replace("5","५").Replace("6","६").Replace("7","७").Replace("8","८").Replace("9","९").Replace("0","०").ToString()+ vbNewLine)
						richTextBox1.AppendText("Weighment time-"+ Convert.ToDateTime(dr("vout_time")).ToString("dd/MM/yyyy h:mm:ss tt")+ vbNewLine)
						richTextBox1.SelectionColor = Color.Green
						richTextBox1.AppendText(sb.ToString() + " on Mobile No-" + dr("mobile_no").ToString()+" "+DateTime.now.ToString("dd/MM/yyyy h:mm:ss tt") + vbNewLine)
					Else
						updatesmsstatus(dr("vyear_code"),dr("nslip_no"),0)
						richTextBox1.SelectionColor = Color.Black
						richTextBox1.AppendText(dr("msg").ToString()+ vbNewLine)
						richTextBox1.SelectionColor = Color.Red
						richTextBox1.AppendText(sbn.ToString() + " " +DateTime.now.ToString("dd/MM/yyyy h:mm:ss tt") + vbNewLine)
					End If
					richTextBox1.Refresh
				End While
			End Using
			Catch ae As Exception
				'MsgBox(ae.Message())
				richTextBox1.SelectionColor = Color.Red
				richTextBox1.AppendText("Next SMS" + ae.Message().ToString()+ vbNewLine)
				Timer1.Enabled=True
		End Try
	End Sub
	
	Sub MainFormLoad(sender As Object, e As EventArgs)
		Try
			conn = New OracleConnection(oradb)
			conn.Open
			'Timer1Tick(sender,e)
		Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Sub
	
	Sub MainFormShown(sender As Object, e As EventArgs)
		Try
			'Timer1Tick(sender,e)
		Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Sub
	
	Sub Timer1Tick(sender As Object, e As EventArgs)
		Timer1.Enabled=False
		If (richTextBox1.TextLength > 100000)
			richTextBox1.Text = ""
		End if
		getnewslip("2018-2019")
		Timer1.Enabled=True		
	End Sub
	
	Sub BtnExitClick(sender As Object, e As EventArgs)
		End		
	End Sub
	
	Sub BtnFarmerPaymentClick(sender As Object, e As EventArgs)
		Dim fortnightselection1 As New fortnightselection
		fortnightselection1.Show()
	End Sub
End Class