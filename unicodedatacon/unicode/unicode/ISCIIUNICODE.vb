'
' Created by SharpDevelop.
' User: admin
' Date: 12/12/2018
' Time: 15:18
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Imports System.Text
Public Class ISCIIUNICODE
     Declare Function Iscii2Isfoc Lib "ismapi32.dll" (ByVal s1 As String,
     	ByVal s2 As String, ByVal ilen As Integer, ByVal sc As String) As Integer
     Declare Function Isfoc2Iscii Lib "ismapi32.dll" (ByVal s1 As String,
     	ByVal s3 As String, ByVal ilen As Integer, ByVal sc As String) As Integer
    'Declare Function Isfoc2Iscii Lib "ismapi32.dll" (ByVal s1 As String,
       '  ByVal s2 As String, ByVal ilen As Integer, ByVal sc As String) As Integer
    'Declare Function Iscii2AccessIsfoc Lib "ismapi32.dll" (ByVal s1 As String,
        ' ByVal s2 As String, ByVal ilen As Integer, ByVal sc As String) As Integer
    ' sc = "DVB"   Devanagari  B-  Bilingial
    '                      Devanagari (DV - catering to Hindi, Marathi, etc.),
    '                     Gujarati (GJ), Kannada (KN), Malayalam (ML), Manipuri
    '                     (MN), Oriya (OR), Punjabi (PN), Tamil (TM), Telugu (TL)
                           

   Public Function IsciiToIsfoc(ByVal str1 As String,ByVal sc as String ) As String
        Dim stext1 As String
        Dim ilen As Integer
        Dim iret As Integer
        stext1 = Space(str1.Length * 2) 'I Don't know   why it has to multiplied by 2
        ilen = str1.Length

        iret = Iscii2Isfoc(str1, stext1, ilen, sc)

        IsciiToIsfoc = stext1

   End Function
   
   Public Function IsfocToIscii(ByVal str1 As String,ByVal sc as String ) As String
        Dim stext1 As String
        Dim ilen As Integer
        Dim iret As Integer
        stext1 = Space(str1.Length * 2) 'I Don't know   why it has to multiplied by 2
        ilen = str1.Length

        iret = Isfoc2Iscii(str1, stext1, ilen, sc)

        IsfocToIscii = stext1

   End Function
   
  
   Public Shared Function UNICODETOISCII(ByVal S As String) As String
    If S Is Nothing Then
        Return Nothing
    End If

    Try
        Dim encFrom As Encoding = Encoding.GetEncoding(57002)
        Dim encTo As Encoding = Encoding.GetEncoding(1252)
        Dim str As String = S
        Dim b As Byte() = encFrom.GetBytes(str)
        Return encTo.GetString(b)
    Catch
        Return Nothing
    End Try
	End Function
	
	Public Shared Function ISCIITOUNICODE(ByVal S As String) As String
		Dim EncFrom As Encoding = Encoding.GetEncoding(1252)
        Dim EncTo as Encoding = Encoding.GetEncoding(57002)
        Dim b As Byte() = EncFrom.GetBytes(S)

        return EncTo.GetString(b)
	End Function

End Class

