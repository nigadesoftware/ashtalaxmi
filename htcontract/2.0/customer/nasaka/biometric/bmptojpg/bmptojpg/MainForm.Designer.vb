'
' Created by SharpDevelop.
' User: ADMIN
' Date: 11-07-2019
' Time: 21:23
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Partial Class MainForm
	Inherits System.Windows.Forms.Form
	
	''' <summary>
	''' Designer variable used to keep track of non-visual components.
	''' </summary>
	Private components As System.ComponentModel.IContainer
	
	''' <summary>
	''' Disposes resources used by the form.
	''' </summary>
	''' <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
	Protected Overrides Sub Dispose(ByVal disposing As Boolean)
		If disposing Then
			If components IsNot Nothing Then
				components.Dispose()
			End If
		End If
		MyBase.Dispose(disposing)
	End Sub
	
	''' <summary>
	''' This method is required for Windows Forms designer support.
	''' Do not change the method contents inside the source code editor. The Forms designer might
	''' not be able to load this method if it was changed manually.
	''' </summary>
	Private Sub InitializeComponent()
		Me.btnConvert = New System.Windows.Forms.Button()
		Me.SuspendLayout
		'
		'btnConvert
		'
		Me.btnConvert.Location = New System.Drawing.Point(81, 81)
		Me.btnConvert.Name = "btnConvert"
		Me.btnConvert.Size = New System.Drawing.Size(117, 39)
		Me.btnConvert.TabIndex = 0
		Me.btnConvert.Text = "Convert"
		Me.btnConvert.UseVisualStyleBackColor = true
		AddHandler Me.btnConvert.Click, AddressOf Me.Button1Click
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.ClientSize = New System.Drawing.Size(284, 160)
		Me.Controls.Add(Me.btnConvert)
		Me.Name = "MainForm"
		Me.Text = "bmptojpg"
		Me.ResumeLayout(false)
	End Sub
	Private btnConvert As System.Windows.Forms.Button
End Class
