'
' Created by SharpDevelop.
' User: admin
' Date: 12/5/2019
' Time: 6:25 PM
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
	'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 18:50
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports MySql.Data.MySqlClient
Imports System
Imports System.Data.OracleClient
Public Module dataconversionmodule
	Public oradb As String = "Data Source=(DESCRIPTION=" _
           + "(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))" _
           + "(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME=orcl)));" _
           + "User Id=swapp_karmaveer_webpub;Password=swapp123;"
    Public conn As OracleConnection
    Public billperiodno As Integer
    Public qr As String
    Public Sub StartConnection()
    	conn = New OracleConnection(oradb)
  		conn.Open
    End Sub
    
    Public Sub StopConnection()
   		conn.Close
    End Sub
End Module
