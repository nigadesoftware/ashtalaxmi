'
' Created by SharpDevelop.
' User: admin
' Date: 05/06/2019
' Time: 10:40
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
'Imports Oracle.DataAccess.Client
Imports System.Data
'Imports MySql.Data.MySqlClient
Imports System
Imports System.Data.OracleClient
Public Partial Class MainForm
	Public wb As weighbridge	
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Sub Button1Click(sender As Object, e As EventArgs)
		Try
            	wb.open()
            	if wb.checkrawdataallowed()=true then
            		wb.Read()
            		wb.updateweight()
            		txtWeight.Text=wb.weight.ToString()
            	End If
		Catch ex As Exception
	            MsgBox(ex.Message)
	            Exit Sub
		End Try		
	End Sub
	
	Sub Timer1Tick(sender As Object, e As EventArgs)
		Button1Click(sender,e)
	End Sub
	
	Sub MainFormLoad(sender As Object, e As EventArgs)
		Try
                	conn = New OracleConnection(oradb)
                	conn.Open()
                	wb = New weighbridge(conn)
                	If wb.katacode <> 0 then
	            	wb.setkataparameter()
	            	lblKataNumber.Text = wb.katacode
	            	Timer1.Enabled=True
                	Else
				lblMacAddress.Text = wb.macaddress                		
            	End if
		Catch ex As Exception
	            MsgBox(ex.Message)
	            Exit Sub
		End Try
	End Sub
End Class
