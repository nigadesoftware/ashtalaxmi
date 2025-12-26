'
' Created by SharpDevelop.
' User: adminImports System.Data
Imports MySql.Data.MySqlClient
Imports System
Imports System.Data.OracleClient
' Date: 02/06/2019
' Time: 17:31
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'Imports Oracle.DataAccess.Client

Public Module wbmodule
	Public oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.36)(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=orclkadwa)));" _
           + "User Id=nst_nasaka_agriculture;Password=swapp123;"
	'Public oradb as String = "Data Source=swapp100;User Id=agriculture;Password=swapp123;"
	Public conn As OracleConnection
	Public currentkatacode As Integer
	Public currentshiftcode As Integer
	Public yearcode As Long
	Public kataoperatorcode As Integer
End Module
