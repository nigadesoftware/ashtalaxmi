'
' Created by SharpDevelop.
' User: admin
' Date: 08/01/2019
' Time: 16:32
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports System.Web
Imports System.IO
Imports System.Net
Imports System.Text
Imports System.Resources
 
Public Class SMS
	Public mobile As string 
	Public message As String
	Public sender As String 
	Public url As String
	Public username As String
	Public password As String
	Public type As String
	Public istest As Boolean
	Public istransportverbose As Boolean
    Public Function sendSMS() As String
        Dim strPost As String
        strPost = url + "username=" + username _
		+ "&password=" + password _        	
        + "&mobilenumber=" + mobile _
        + "&message=" + WebUtility.UrlEncode(message) _
        + "&senderid=" + sender _
        + "&type=" + type
        If url = "" Then
			strPost += "&url=" + url
		End If
		If istest = True Then
			strPost += "&test=1" 
		End If
		If istransportverbose =True Then
			strPost += "&info=1"
		End If
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
    End Function
End Class