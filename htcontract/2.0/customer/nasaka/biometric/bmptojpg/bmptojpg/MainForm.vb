'
' Created by SharpDevelop.
' User: ADMIN
' Date: 11-07-2019
' Time: 21:23
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Public Partial Class MainForm
	Public Sub New()
		' The Me.InitializeComponent call is required for Windows Forms designer support.
		Me.InitializeComponent()
		
		'
		' TODO : Add constructor code after InitializeComponents
		'
	End Sub
	
	Sub Button1Click(sender As Object, e As EventArgs)
		' TODO: Implement Functionality Here
		Dim _sourcefilename$ = "\"
        Dim _destinationfilename$ = "C:\Program Files\Mantra\MFS100\Driver\MFS100Test\FingerData\FingerImage.jpg"
        Dim FileDelete As String
        FileDelete = "C:\Program Files\Mantra\MFS100\Driver\MFS100Test\FingerData\FingerImage.jpg"
        If System.IO.File.Exists( FileDelete ) = True Then
		   System.IO.File.Delete( FileDelete )
		   'MsgBox("File Deleted")
        End If
        
 		JpegSaveClass.SaveImage(_sourcefilename, _destinationfilename)
 		
 		

		FileDelete = "C:\Program Files\Mantra\MFS100\Driver\MFS100Test\FingerData\FingerImage.bmp"
		
		 If System.IO.File.Exists( FileDelete ) = True Then
		   System.IO.File.Delete( FileDelete )
		   'MsgBox("File Deleted")
		 End If	
		 MsgBox("Sucessfully Converted")
	End Sub
End Class
Public Class JpegSaveClass
        Private Shared Function GetEncoderInfo(ByVal mimeType As String) As System.Drawing.Imaging.ImageCodecInfo
            Dim j As Integer
            Dim encoders() As System.Drawing.Imaging.ImageCodecInfo
            encoders = System.Drawing.Imaging.ImageCodecInfo.GetImageEncoders()
            j = 0
            While j < encoders.Length
                If encoders(j).MimeType = mimeType Then
                    Return encoders(j)
                End If
                j += 1
            End While
            Return Nothing
        End Function 'GetEncoderInfo
     
     
        Public Shared Function SaveImage(ByVal _bmpFilename$, ByVal _destinationFilename$) As Boolean
            Try
                If IsNothing(_bmpFilename$) Then Return False
     
                Dim _btmp As New System.Drawing.Bitmap(_bmpFilename$)
                Dim jgpEncoder As System.Drawing.Imaging.ImageCodecInfo = GetEncoderInfo("image/jpeg")
                Dim _encoder As System.Drawing.Imaging.Encoder = System.Drawing.Imaging.Encoder.Quality
                Dim _encoderParameters As New System.Drawing.Imaging.EncoderParameters(1)
                Dim myEncoderParameter As New System.Drawing.Imaging.EncoderParameter(_encoder, 50&)
     
                _encoderParameters.Param(0) = myEncoderParameter
                _btmp.Save(_destinationFilename$, jgpEncoder, _encoderParameters)
                _btmp.Dispose()
     
                Return True
            Catch ex As Exception
                Return False
            End Try
        End Function
     
 End Class