'
' Created by SharpDevelop.
' User: admin
' Date: 24/01/2019
' Time: 14:38
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Partial Class voucherdetail
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Sub convertdetail()
		Try 
			Dim sql As String = "select ntrans_no,nsr_no,accountcode,vind_code,subledgercode,nvl(ncr_amt,0) as ncr_amt,nvl(ndr_amt,0) as ndr_amt,nvou_sub_type from (Select t.*,m.accountcode,null as subledgercode,h.nvou_sub_type from ac_voucher_header h,ac_voucher_detail t,accounthead m where h.ntrans_no=t.ntrans_no "  & _
			"And h.vfin_year='2019-2020' And h.nvou_status=9 and t.nac_code=m.refcode  and t.vind_code is null union all Select t.*,m.accountcode,s.subledgercode,h.nvou_sub_type from ac_voucher_header h "  & _ 
			",ac_voucher_detail t,accounthead m ,accountsubledger s where h.ntrans_no=t.ntrans_no And h.vfin_year='2019-2020' And h.nvou_status=9 and t.nac_code=m.refcode and m.accountcode=s.accountcode "  & _ 
			"and t.vind_code=s.referencecode(+)) Order By ntrans_no,nsr_no"
			StartConnection()
			Dim cmd As New OracleCommand(sql, conn)
			cmd.CommandType = CommandType.Text
			Dim indcode As String
			Dim iscashentry As Boolean
			Dim drcr As String 
			Dim amt As Double=0
			Dim srno As Integer=1
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblTrans_no.Text = dr("ntrans_no").ToString()
				If (dr("nvou_sub_type")=1) Then
					iscashentry = True
					drcr = "Dr"
				ElseIf (dr("nvou_sub_type")=2) Then
					iscashentry = True
					drcr = "Cr"
				Else
					iscashentry = False
				End If
				
				If (dr("vind_code").ToString()= "")
					indcode = "null"
				Else
					indcode =dr("subledgercode").ToString()
				End If
				cmd.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (" + dr("ntrans_no").ToString() + "," + srno.ToString() + ",1200001," + indcode + "," + dr("ncr_amt").ToString() + "," + dr("ndr_amt").ToString() + ")"
				qr=cmd.CommandText
				cmd.ExecuteNonQuery()
				lblTrans_no.Refresh
				If (iscashentry = True And drcr="Dr") Then
					amt=amt+dr("ncr_amt")
				Else If (iscashentry = True And drcr="Cr") Then
					amt=amt+dr("ndr_amt")
				Else
					amt=0
				End If
				
				srno=srno+1
			End While
				
					
			If (iscashentry = True) Then	
				If (drcr = "Dr") Then
					cmd.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (" + dr("ntrans_no").ToString() + "," +srno.ToString() + ",1200001,null,0," + amt.ToString() + ")"	
				Else	
					cmd.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (" + dr("ntrans_no").ToString() + "," + srno.ToString() + ",1200001,null," + amt.ToString() + ",0)"
				End If
				
				qr=cmd.CommandText
				cmd.ExecuteNonQuery()
				cmd.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (" + dr("ntrans_no").ToString() + "," + dr("nsr_no").ToString() + "," + dr("accountcode").ToString() + "," + indcode + "," + dr("ncr_amt").ToString() + "," + dr("ndr_amt").ToString() + ")"
				qr=cmd.CommandText
				cmd.ExecuteNonQuery()
				
				lblTrans_no.Refresh
			end if
			StopConnection()
			lblTrans_no.Text="Record Converted"
			Catch ae As Exception
			MsgBox(ae.Message()+qr.ToString())
		End Try
	End Sub
	
	Sub Btn_voucherdetailconvertClick(sender As Object, e As EventArgs)
		convertdetail()
	End Sub
End Class
