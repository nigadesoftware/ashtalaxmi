Public Class myStartupClass

''' <summary>
''' This is the method that will be run when the application loads, 
''' because Project Properties, Startup Object is set to SubMain
''' </summary>
''' <remarks>
''' </remarks>
''' --------------------------------------------------------------------------------
Public Shared Sub Main()

    'The form that we will end up showing
    Dim formToShow As System.Windows.Forms.Form = Nothing

    formToShow = New MainForm

    'Show the form, and keep it open until it's explicitly closed.
    formToShow.ShowDialog()

End Sub
End Class
