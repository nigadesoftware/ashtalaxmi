'
' Created by SharpDevelop.
' User: admin
' Date: 12/01/2019
' Time: 20:23
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports Oracle.DataAccess.Client
Imports System.Data
Imports System.Data.SqlClient
Public Module smsmodule
	Public oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.1.36)(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=orclkadwa)));" _
           + "User Id=nst_nasaka_agriculture;Password=swapp123;"
    Public conn As OracleConnection
	Public billperiodno As Integer
End Module
