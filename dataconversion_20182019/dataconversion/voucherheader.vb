'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 18:48
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Partial Class voucherheader
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Function numberseries(vousubtypecode As Integer)
		Try 
        	Dim sql As String = "select h.vouchernumberseriesid from vouchersubtype h where vouchersubtypecode=" + vousubtypecode.ToString()
			StartConnection()
			Dim cmd As New OracleCommand(sql, conn)
			Dim vouchernumberseriesid As Integer
			cmd.CommandType = CommandType.Text
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			if dr.Read()
				vouchernumberseriesid = dr("vouchernumberseriesid")
			Else
				vouchernumberseriesid = 0
			End If
			dr.Close()
			Return vouchernumberseriesid
			Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Function
	
	Sub convertheader()
		Try 
        	Dim sql As String = "select h.*,case when nvou_sub_type<>7 then vouchernumberbasevalue(h.dvou_date,2) else vouchernumberbasevalue(h.dvou_date,4) end as voucherbase from ac_voucher_header@erpold h where vfin_year='2018-2019' and nvou_status=9 order by ntrans_no"
			StartConnection()
			Dim cmd As New OracleCommand(sql, conn)
			Dim narration_uni As String
			Dim description_uni As String
			Dim vousubtype As Integer
			Dim vouwithprefix As String 
			cmd.CommandType = CommandType.Text
			Dim str1 As String
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblTrans_no.Text = dr("ntrans_no").ToString()
				If (dr("vnarration").ToString()<>"") Then
					lblNarration.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr("vnarration"),"DVB")).ToString()
				Else
					lblNarration.Text = "null"
				End If
				If (dr("vrec_from").ToString()<>"") Then
					lblDescription.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr("vrec_from").ToString(),"DVB"))	
				Else
					lblDescription.Text = "null"
				End If
				
				If (lblNarration.Text<>"null") Then
					lblNarration.Text = "'"+lblNarration.Text+"'"
				End If
				
				If (lblDescription.Text<>"null") Then
					lblDescription.Text = "'"+lblDescription.Text+"'"
				End If
				
				If(dr("nvou_sub_type")=1) Then
					vousubtype = 1
					vouwithprefix = "CR"
				elseIf(dr("nvou_sub_type")=2) Then
					vousubtype = 4
					vouwithprefix = "CP"
				elseIf(dr("nvou_sub_type")=3) Then
					vousubtype = 3
					vouwithprefix = "BR"
				elseIf(dr("nvou_sub_type")=4) Then
					vousubtype = 5
					vouwithprefix = "BP"
				elseIf(dr("nvou_sub_type")=5) Then
					vousubtype = 9
					vouwithprefix = "TR"
				elseIf(dr("nvou_sub_type")=6) Then
					vousubtype = 19
					vouwithprefix = "JV"
				elseIf(dr("nvou_sub_type")=7) Then
					vousubtype = 14
					vouwithprefix = "JS"
				End If
				vouwithprefix = vouwithprefix + dr("nvou_number").ToString().Trim
				cmd.CommandText = "insert into voucherheader(transactionnumber, legalentitycode, yearperiodcode, vouchersubtypecode, vouchernumberbasevalue, vouchernumber, vouchernumberprefixsufix, voucherdate, description, narration, approvalstatus, vouchernumberseriesid)" & _
					"	values (20182019*1000000+" + dr("ntrans_no").ToString()+"+0" + ",1,20182019," + vousubtype.ToString() + ",'" + dr("voucherbase").ToString() + "'," + dr("nvou_number").ToString() + ",'" + vouwithprefix.ToString() + "','" + Convert.ToDateTime(dr("dvou_date")).ToString("dd-MMM-yyyy") + "'," + lblDescription.Text + "," + lblNarration.Text  + ",9," + numberseries(vousubtype).ToString() + ")"
				cmd.ExecuteNonQuery()
				
				Dim sql1 As String = "select ntrans_no,nsr_no,accountcode,vind_code,subledgercode,nvl(ncr_amt,0) as ncr_amt,nvl(ndr_amt,0) as ndr_amt,nvou_sub_type from (Select t.*,m.accountcode,null as subledgercode,h.nvou_sub_type from ac_voucher_header@erpold h,ac_voucher_detail@erpold t,accounthead m where h.ntrans_no=t.ntrans_no "  & _
			"And h.vfin_year='2018-2019' And h.nvou_status=9 and t.nac_code=m.refcode  and t.vind_code is null union all Select t.*,m.accountcode,s.subledgercode,h.nvou_sub_type from ac_voucher_header@erpold h "  & _ 
			",ac_voucher_detail@erpold t,accounthead m ,accountsubledger s where h.ntrans_no=t.ntrans_no And h.vfin_year='2018-2019' And h.nvou_status=9 and t.nac_code=m.refcode and m.accountcode=s.accountcode "  & _ 
			"And t.vind_code=s.referencecode(+)) " & _
			"where ntrans_no	= " + dr("ntrans_no").ToString()  & _
			" Order By ntrans_no,nsr_no"

			Dim cmd1 As New OracleCommand(sql1, conn)
			cmd1.CommandType = CommandType.Text
			Dim indcode As String
			Dim iscashentry As Boolean
			Dim isbankentry As Boolean
			Dim drcr As String 
			Dim amt As Double=0
			Dim srno As Integer=1
			Dim bankaccountcode As Integer=0
			Dim dr1 As OracleDataReader = cmd1.ExecuteReader()
			While dr1.Read()
				lblTrans_no.Text = dr1("ntrans_no").ToString()
				If (dr1("nvou_sub_type")=1) Then
					iscashentry = True
					isbankentry = False
					drcr = "Dr"
				ElseIf (dr1("nvou_sub_type")=2) Then
					iscashentry = True
					isbankentry = False
					drcr = "Cr"
				ElseIf (dr1("nvou_sub_type")=3 And dr1("ndr_amt")>0 And bankaccountcode=0) Then
					isbankentry = True
					iscashentry = False
					bankaccountcode = dr1("accountcode")
				ElseIf (dr1("nvou_sub_type")=4 And dr1("ncr_amt")>0 And bankaccountcode=0) Then
					isbankentry = True
					iscashentry = False
					bankaccountcode = dr1("accountcode")
				Else
					iscashentry = False
					isbankentry = False
				End If
				
				If (dr1("vind_code").ToString()<>"") Then					
					indcode = dr1("subledgercode").ToString()
				Else
					indcode = "null"
				End If
				cmd1.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (20182019*1000000+" + dr1("ntrans_no").ToString()+"+0" + "," + srno.ToString() + "," + dr1("accountcode").ToString() + "," + indcode + "," + dr1("ncr_amt").ToString() + "," + dr1("ndr_amt").ToString() + ")"
				qr=cmd1.CommandText
				cmd1.ExecuteNonQuery()
				If (iscashentry = True And drcr="Dr") Then
					amt=amt+dr1("ncr_amt")
				Else If (iscashentry = True And drcr="Cr") Then
					amt=amt+dr1("ndr_amt")
				Else
					amt=0
				End If
				
				srno=srno+1
			End While
			dr1.Close()				
					
			If (iscashentry = True) Then	
				If (drcr = "Dr") Then
					cmd1.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (20182019*1000000+" + dr("ntrans_no").ToString()+"+0" + "," +srno.ToString() + ",1200001,null,0," + amt.ToString() + ")"	
				Else	
					cmd1.CommandText = "insert into voucherdetail(transactionnumber, detailserialnumber,accountcode,subledgercode,credit,debit)" & _
					"	values (20182019*1000000+" + dr("ntrans_no").ToString()+"+0" + "," + srno.ToString() + ",1200001,null," + amt.ToString() + ",0)"
				End If
				
				qr=cmd1.CommandText
				cmd1.ExecuteNonQuery()
			End If
			
			Dim sql2 As String = "Select ntype, nnumber, ddate, vdrawee_bank, namount " & _
				"				from ac_voucher_cheque_dd_detail@erpold t" & _
				" 				where t.nvou_trans_no=" + dr("ntrans_no").ToString()

			Dim cmd2 As New OracleCommand(sql2, conn)
			cmd2.CommandType = CommandType.Text
			Dim type As Integer
			Dim srno1 As Integer=1
			Dim dr2 As OracleDataReader = cmd2.ExecuteReader()
			While dr2.Read()
				lblTrans_no.Text = dr("ntrans_no").ToString()
				If (dr2("ntype")=1) Then
					type =1
				ElseIf (dr2("ntype")=2) Then
					type =2
				ElseIf (dr2("ntype")=3) Then
					type =3
				ElseIf (dr2("ntype")=4) Then
					type =10
				Else
					type =0
				End If
				If (dr2("vdrawee_bank").ToString()<>"") Then
					lblNarration.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr2("vdrawee_bank"),"DVB")).ToString()
				Else
					lblNarration.Text = "null"
				End If
				If (lblNarration.Text<>"null") Then
					lblNarration.Text = "'"+lblNarration.Text+"'"
				End If
				If (bankaccountcode=0) Then
					bankaccountcode="null"
				else
				End If
				cmd2.CommandText = "insert into voucherchequedddetail(transactionnumber, detailserialnumber,bankaccountcode,funddocumentcode,funddocumentnumber,funddocumentdate,funddocumentamount,draweebank)" & _
					"	values (20182019*1000000+" + dr("ntrans_no").ToString()+"+0" + "," + srno1.ToString() + "," + bankaccountcode.ToString() + "," + type.ToString() +"," + dr2("nnumber").ToString() + ",'" + Convert.ToDateTime(dr2("ddate")).ToString("dd-MMM-yyyy") + "'," + dr2("namount").ToString() + "," + lblNarration.Text + ")"
				qr=cmd2.CommandText
				cmd2.ExecuteNonQuery()
				
				srno1=srno1+1
			End While
			dr2.Close()				
			
				lblTrans_no.Refresh
				lblNarration.Refresh
				lblDescription.Refresh
			End While
			StopConnection()
			lblTrans_no.Text="Record Converted"
			Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Sub
	
	Sub Btn_voucherheaderconvertClick(sender As Object, e As EventArgs)
			convertheader()
	End Sub
	
	Sub Btn_voucherdetailconvertClick(sender As Object, e As EventArgs)
		Dim voucherdetail1 As New voucherdetail
		voucherdetail1.Show()
	End Sub
End Class
