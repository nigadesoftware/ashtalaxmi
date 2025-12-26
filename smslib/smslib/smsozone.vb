'
' Created by SharpDevelop.
' User: admin
' Date: 28/05/2019
' Time: 15:54
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
	Imports System.Web
	Imports System.IO
	Imports System.Net
	Imports System.Text
	Imports System.Resources
Public Class smsozone
	Public mobile As string 
	Public message As String
	Public sender As String 
	Public url As String
	Public username As String
	Public password As String
	Public channel As String 
	Public dcs As String 
	Public flashsms As String
	Public route As String 
	Public templateid As String
	Public peid As String 
    Public Function sendSMS() As String
        Dim strPost As String
        strPost = url + "user=" + username _
		+ "&password=" + password _        	
        + "&number=" + mobile _
        + "&text=" + WebUtility.UrlEncode(message) _
        + "&senderid=" + sender _
        + "&channel=" + channel _
        + "&DCS=" + dcs _
        + "&flashsms=" + flashsms _
        + "&route=" + route _
        + "&templateid=" + templateid _
        + "&peid=" + peid
        'Try
	        Dim request As WebRequest = WebRequest.Create(strPost)
	        request.Method = "POST"
	        Dim byteArray As Byte() = Encoding.UTF8.GetBytes(strPost)
	        request.ContentType = "application/x-www-form-urlencoded"
	        request.ContentLength = byteArray.Length
	        Dim dataStream As Stream = request.GetRequestStream()
	        dataStream.Write(byteArray, 0, byteArray.Length)
	        dataStream.Close()
	        
	        Dim response As WebResponse = request.GetResponse()
	        dataStream = response.GetResponseStream()
	        Dim reader As New StreamReader(dataStream)
	        Dim responseFromServer As String = reader.ReadToEnd()
	        Console.WriteLine(responseFromServer)
	        Console.ReadLine()
	        
	        reader.Close()
	        dataStream.Close()
	        response.Close()
	        
	        If responseFromServer.Length > 0 Then
	            Return responseFromServer
	        Else
	            Return CType(response, HttpWebResponse).StatusDescription
	        End If
        'Catch ex As Exception
        	'Return ex.ToString
        'End Try
    End Function

End Class
