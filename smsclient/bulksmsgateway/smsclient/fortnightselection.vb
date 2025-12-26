'
' Created by SharpDevelop.
' User: admin
' Date: 12/01/2019
' Time: 11:48
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports System.Collections.Generic
Imports System.Windows.Forms
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Partial Class fortnightselection
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Sub GroupBox1Enter(sender As Object, e As EventArgs)
		
	End Sub
	
	Sub FortnightselectionLoad(sender As Object, e As EventArgs)
        Try
			'conn1 = New OracleConnection(oradb)
			'conn1.Open
			Dim sql As String = "select vyear_code from com_year_master order by to_number(substr(vyear_code,1,4)) desc"
			Dim sql1 As String = "select vbill_name_uni,nbill_code from cab_bill_type_master order by nbill_code"
			Dim cmd As New OracleCommand(sql, conn)
			Dim cmd1 As New OracleCommand(sql1, conn)
			Dim dtAdjuster As New DataTable
			Dim dtAdjuster1 As New DataTable
			dtAdjuster.Columns.add("vyear_code")
			dtAdjuster.Columns.add("year_code")
			dtAdjuster1.Columns.add("vbill_name_uni")
			dtAdjuster1.Columns.add("nbill_code")
			
			'dtAdjuster.loaddatarow(new object() {"Martin", "1"},true)
			'dtAdjuster.loaddatarow(new object() {"Cor","2"},true)


			cmd.CommandType = CommandType.Text
			Dim dr As OracleDataReader = cmd.ExecuteReader()
    		Using dr
				While dr.Read()
					dtAdjuster.loaddatarow(new object() {dr("vyear_code").ToString(),dr("vyear_code").ToString()},true)
				End While
    		End Using
    		cboYear.datasource = dtAdjuster
			cboYear.displaymember = "vyear_code"
			cboYear.Valuemember = "year_code"
			
			cmd1.CommandType = CommandType.Text
			Dim dr1 As OracleDataReader = cmd1.ExecuteReader()
    		Using dr1
				While dr1.Read()
					dtAdjuster1.loaddatarow(new object() {dr1("vbill_name_uni").ToString(),dr1("nbill_code").ToString()},true)
				End While
    		End Using
    		cboBillType.datasource = dtAdjuster1
			cboBillType.displaymember = "vbill_name_uni"
			cboBillType.Valuemember = "nbill_code"

		Catch ae As Exception
			msgbox (ae.Message)
		End Try
	End Sub
	
	Sub BtnSubmitClick(sender As Object, e As EventArgs)
		Dim sql As String = "select nbill_period_no from cab_bill_period_master@sugarerplink p where issmssent is null and nposted=1 and vyear_code='" _ 
		+ cboYear.SelectedValue + "' and nbill_type=" + cboBillType.SelectedValue + " and nfortnight_no=" + txtFortnightNo.Text
		Dim cmd As New OracleCommand(sql, conn)
		cmd.CommandType = CommandType.Text
		Dim dr As OracleDataReader = cmd.ExecuteReader()
		Using dr
			if dr.Read()
				billperiodno = dr("nbill_period_no")
				me.Hide 
				Dim farmerfortnightsms1 As New farmerfortnightsms
				farmerfortnightsms1.Show
			Else
				billperiodno = 0
				MsgBox ("Invalid Selection")
			End if
		End Using
	End Sub
End Class
