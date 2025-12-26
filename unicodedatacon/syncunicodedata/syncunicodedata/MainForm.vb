'
' Created by SharpDevelop.
' User: admin
' Date: 26/12/2018
' Time: 18:32
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
	Sub BtnFarmerClick(sender As Object, e As EventArgs)
    	updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","com_farmer_master","nfarmer_code","vfarmer_name_isf","vfarmer_name_uni")
	End Sub
	
	Sub MainFormLoad(sender As Object, e As EventArgs)
		Timer1Tick(sender,e)		
	End Sub
	
	Sub BtnVillageClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","com_village_master","nvill_code","vvill_name_isf","vvill_name_uni")
	End Sub
	
	Sub updateunicodedata(orahost As String,oraservice As String,dblink As String,dbuser As String, dbpwd As String,tablename As String,codefield As String,fieldname_isf As String, fieldname_uni As String)
		Try 
        	Dim oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST="+ orahost +")(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME="+ oraservice +")));" _
           + "User Id="+ dbuser +";Password="+ dbpwd +";"
        	Dim conn As New OracleConnection(oradb)
        	Dim inssql As String = "insert into "+ tablename + " select * from "+ tablename +"@"+dblink+" t " _
			+ "where "+ codefield +" in (" _
			+ "select "+ codefield +" from "+ tablename +"@"+dblink+" t " _
			+ "minus " _
			+ "select "+ codefield +" from "+ tablename +" v)"
        	Dim sql As String = "select "+ codefield +","+ fieldname_isf +" from "+ tablename +" where "+ fieldname_isf +" is not null and "+ fieldname_uni +" is null order by "+ codefield
			conn.Open()
			Dim cmd2 As OracleCommand = conn.CreateCommand()
			cmd2.CommandType = CommandType.Text	
			cmd2.CommandText = inssql
			cmd2.ExecuteNonQuery()
			Dim cmd As New OracleCommand(sql, conn)
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			cmd.CommandType = CommandType.Text
			cmd1.CommandType = CommandType.Text	
			
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblCode.Text = dr(codefield).ToString()
				lblNameUni.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr(fieldname_isf).ToString(),"DVB"))
				cmd1.CommandText = "update "+ tablename +" set "+ fieldname_uni +" = '" & lblNameUni.Text & "' where "+ codefield +" = " & dr(codefield)
				cmd1.ExecuteNonQuery()
				lblCode.Refresh
				lblNameUni.Refresh
			End While
			conn.Close
			
'			sql = "select "+ codefield +","+ fieldname_isf +" from "+ tablename +"@"+dblink+" where "+ fieldname_isf +" is not null and "+ fieldname_uni +" is null and rownum<=100 order by "+ codefield
'			'conn.Open()
'			cmd.CommandText = sql
'						
'			Dim str As New Unicode.ISCIIUNICODE
'			dr = cmd.ExecuteReader()
'			While dr.Read()
'				lblCode.Text = dr(codefield).ToString()
'				lblNameUni.Text = str.ISCIITOUNICODE(str.IsfocToIscii(dr(fieldname_isf).ToString(),"DVB"))
'				cmd1.CommandText = "update "+ tablename +"@"+dblink+" set "+ fieldname_uni +" = '" & lblNameUni.Text & "' where "+ codefield +" = " & dr(codefield)
'				cmd1.ExecuteNonQuery()
'				lblCode.Refresh
'				lblNameUni.Refresh
'			End While
			conn.Close
			lblCode.Text = ""
			lblCode.Refresh
			lblNameUni.Text=tablename+" is updated"
			lblNameUni.Refresh
			Catch ae As Exception
			MsgBox(tablename+" "+ae.Message())
		End Try
	End Sub
	
	Sub updateunicodedata_isc(orahost As String,oraservice As String,dblink As String,dbuser As String, dbpwd As String,tablename As String,codefield As String,fieldname_isc As String, fieldname_uni As String)
		Try 
        	Dim oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST="+ orahost +")(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME="+ oraservice +")));" _
           + "User Id="+ dbuser +";Password="+ dbpwd +";"
        	Dim conn As New OracleConnection(oradb)
        	Dim inssql As String = "insert into "+ tablename + "(nbranch_code, vbranch_name, veng_branch_name, nbranch_bank_type, vbranch_bank_address, vbranch_phone_no, vbranch_email_address, dcreate_date, dupdate_date, vcreate_user, vupdate_user, nbank_code, nbank_branch_vill_code, vbranch_short_name, vbranch_name_isf, ncollection_charges, nold_bank_code, vifsc_code) select nbranch_code, vbranch_name, veng_branch_name, nbranch_bank_type, vbranch_bank_address, vbranch_phone_no, vbranch_email_address, dcreate_date, dupdate_date, vcreate_user, vupdate_user, nbank_code, nbank_branch_vill_code, vbranch_short_name, vbranch_name_isf, ncollection_charges, nold_bank_code, vifsc_code from "+ tablename +"@"+dblink+" t " _
			+ "where "+ codefield +" in (" _
			+ "select "+ codefield +" from "+ tablename +"@"+dblink+" t " _
			+ "minus " _
			+ "select "+ codefield +" from "+ tablename +" v)"
        	Dim sql As String = "select "+ codefield +","+ fieldname_isc +" from "+ tablename +" where "+ fieldname_isc +" is not null and "+ fieldname_uni +" is null order by "+ codefield
			conn.Open()
			Dim cmd2 As OracleCommand = conn.CreateCommand()
			cmd2.CommandType = CommandType.Text	
			cmd2.CommandText = inssql
			cmd2.ExecuteNonQuery()
			Dim cmd As New OracleCommand(sql, conn)
			Dim cmd1 As OracleCommand = conn.CreateCommand()
			cmd.CommandType = CommandType.Text
			cmd1.CommandType = CommandType.Text	
			
			Dim str As New Unicode.ISCIIUNICODE
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			While dr.Read()
				lblCode.Text = dr(codefield).ToString()
				lblNameUni.Text = str.ISCIITOUNICODE(dr(fieldname_isc).ToString())
				cmd1.CommandText = "update "+ tablename +" set "+ fieldname_uni +" = '" & lblNameUni.Text & "' where "+ codefield +" = " & dr(codefield)
				cmd1.ExecuteNonQuery()
				lblCode.Refresh
				lblNameUni.Refresh
			End While
			conn.Close
			lblCode.Text = ""
			lblCode.Refresh
			lblNameUni.Text=tablename+" is updated"
			lblNameUni.Refresh
			Catch ae As Exception
			MsgBox(tablename+" "+ae.Message())
		End Try
	End Sub
	
	Sub BtnExitClick(sender As Object, e As EventArgs)
		End		
	End Sub
	
	Sub BtnSectionClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","com_section_master","nsect_code","vsect_name_isf","vsect_name_uni")
	End Sub
	
	Sub BtnBankBranchClick(sender As Object, e As EventArgs)
		'updateunicodedata("192.168.1.254","orclweb","nst_nasaka_webpub","swapp123","com_bank_branch_master","nbranch_code","vbranch_name_isf","vbranch_name_uni")
		updateunicodedata_isc("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","com_bank_branch_master","nbranch_code","vbranch_name","vbranch_name_uni")
	End Sub
	
	Sub BtnSocietyClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","cab_society_master","nsociety_code","vsociety_name_isf","vsociety_name_uni")
	End Sub
	
	Sub BtnBillPeriodClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","cab_bill_period_master","nbill_period_no","vbill_name1_isf","vbill_name1_uni")
	End Sub
	
	Sub BtnBillTypeClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","cab_bill_type_master","nbill_code","vbill_name_isf","vbill_name_uni")
	End Sub
	
	Sub BtnDedClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarerplink","nst_nasaka_webpub","swapp123","com_deduction_master","ndeduction_code","vdeduction_name_isf","vdeduction_name_uni")
	End Sub
	
	Sub Timer1Tick(sender As Object, e As EventArgs)
		'BtnBankBranchClick(sender,e)
		Timer1.Enabled=False
		checkrecordstatus(sender,e,"192.168.1.254","orclweb","nst_nasaka_webpub","swapp123")
		Timer1.Enabled=True
	End Sub
	
	Sub checkrecordstatus(sender As Object, e As EventArgs, orahost As String, oraservice As String,dbuser As String, dbpwd As String)
		Try 
        	Dim oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST="+ orahost +")(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME="+ oraservice +")));" _
           + "User Id="+ dbuser +";Password="+ dbpwd +";"
        	Dim conn As New OracleConnection(oradb)
        	Dim sql As String = "select farcount, seccount, villcount, soccount, branchcount, billtypecount, dedcount,trcount,hrcount,blkcount from vw_record_update_status"
			conn.Open()
			Dim cmd As New OracleCommand(sql, conn)
			cmd.CommandType = CommandType.Text
		
			Dim dr As OracleDataReader = cmd.ExecuteReader()
			if dr.Read()
				If (dr("farcount")>0) Then
					BtnFarmerClick(sender,e)
				End If
				If (dr("seccount")>0) Then
					BtnSectionClick(sender,e)
				End If
				If (dr("villcount")>0) Then
					BtnVillageClick(sender,e)
				End If
				If (dr("soccount")>0) Then
					BtnSocietyClick(sender,e)
				End If
				If (dr("branchcount")>0) Then
					BtnBankBranchClick(sender,e)
				End If
				If (dr("billtypecount")>0) Then
					BtnBillTypeClick(sender,e)
				End If
				If (dr("dedcount")>0) Then
					BtnDedClick(sender,e)
				End If
				If (dr("trcount")>0) Then
					BtnTransporterClick(sender,e)
				End If
				If (dr("hrcount")>0) Then
					BtnHarvesterClick(sender,e)
				End If
				If (dr("blkcount")>0) Then
					BtnBullockcartClick(sender,e)
				End If
			End if
			conn.Close
			lblNameUni.Text="Sync is executed at " + TimeOfDay.ToString("h:mm:ss tt")
			lblNameUni.Refresh
			Catch ae As Exception
			MsgBox(ae.Message())
		End Try
	End Sub
	
	Sub BtnTransporterClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarhtlink","nst_nasaka_webpub","swapp123","ht_transporter_master","ntransportor_code","vtransportor_name","vtransportor_name_unicode")
	End Sub
	
	Sub BtnHarvesterClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarhtlink","nst_nasaka_webpub","swapp123","ht_harvester_master","nharvestor_code","vharvestor_name","vharvestor_name_unicode")
	End Sub
	
	Sub BtnBullockcartClick(sender As Object, e As EventArgs)
		updateunicodedata("192.168.1.254","orclweb","sugarhtlink","nst_nasaka_webpub","swapp123","ht_bullock_makadam_master","nbulluckcart_code","vbulluckcart_name","vbulluckcart_name_unicode")
	End Sub
End Class
