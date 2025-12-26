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
			Dim sql As String = "select t.billperiodtransnumber,BILLPERIODDESC(t.billperiodtransnumber) as descr from billperiodheader t where t.payeecategorycode=1 and t.issmssent is null"
			Dim cmd As New OracleCommand(sql, conn)
			Dim dtAdjuster As New DataTable
			Dim dtAdjuster1 As New DataTable
			dtAdjuster.Columns.add("descr")
			dtAdjuster.Columns.add("billperiodtransnumber")
			'dtAdjuster.loaddatarow(new object() {"Martin", "1"},true)
			'dtAdjuster.loaddatarow(new object() {"Cor","2"},true)


			cmd.CommandType = CommandType.Text
			Dim dr As OracleDataReader = cmd.ExecuteReader()
    		Using dr
				While dr.Read()
					dtAdjuster.loaddatarow(new object() {dr("descr").ToString(),dr("billperiodtransnumber").ToString()},true)
				End While
    		End Using
    		cboYear.datasource = dtAdjuster
			cboYear.displaymember = "descr"
			cboYear.Valuemember = "billperiodtransnumber"
			
			
		Catch ae As Exception
			msgbox (ae.Message)
		End Try
	End Sub
	
	Sub BtnSubmitClick(sender As Object, e As EventArgs)
		Dim sql As String = "select billperiodtransnumber from billperiodheader p where issmssent is null and islocked=1 and billperiodtransnumber=" _ 
		+ cboYear.SelectedValue
		Dim cmd As New OracleCommand(sql, conn)
		cmd.CommandType = CommandType.Text
		Dim dr As OracleDataReader = cmd.ExecuteReader()
		Using dr
			if dr.Read()
				billperiodno = dr("billperiodtransnumber")
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
