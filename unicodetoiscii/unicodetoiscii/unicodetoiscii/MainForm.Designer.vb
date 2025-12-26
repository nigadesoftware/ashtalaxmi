'
' Created by SharpDevelop.
' User: admin
' Date: 12/5/2019
' Time: 6:20 PM
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
		Me.button1 = New System.Windows.Forms.Button()
		Me.lblCode = New System.Windows.Forms.Label()
		Me.lblNameUni = New System.Windows.Forms.Label()
		Me.SuspendLayout
		'
		'button1
		'
		Me.button1.Location = New System.Drawing.Point(145, 185)
		Me.button1.Name = "button1"
		Me.button1.Size = New System.Drawing.Size(75, 23)
		Me.button1.TabIndex = 0
		Me.button1.Text = "Conversion"
		Me.button1.UseVisualStyleBackColor = true
		AddHandler Me.button1.Click, AddressOf Me.Button1Click
		'
		'lblCode
		'
		Me.lblCode.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblCode.ForeColor = System.Drawing.Color.Black
		Me.lblCode.Location = New System.Drawing.Point(12, 9)
		Me.lblCode.Name = "lblCode"
		Me.lblCode.Size = New System.Drawing.Size(400, 21)
		Me.lblCode.TabIndex = 8
		Me.lblCode.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'lblNameUni
		'
		Me.lblNameUni.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblNameUni.ForeColor = System.Drawing.Color.Black
		Me.lblNameUni.Location = New System.Drawing.Point(12, 30)
		Me.lblNameUni.Name = "lblNameUni"
		Me.lblNameUni.Size = New System.Drawing.Size(400, 47)
		Me.lblNameUni.TabIndex = 7
		Me.lblNameUni.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.ClientSize = New System.Drawing.Size(424, 262)
		Me.Controls.Add(Me.lblCode)
		Me.Controls.Add(Me.lblNameUni)
		Me.Controls.Add(Me.button1)
		Me.Name = "MainForm"
		Me.Text = "unicodetoiscii"
		Me.ResumeLayout(false)
	End Sub
	Private lblNameUni As System.Windows.Forms.Label
	Private lblCode As System.Windows.Forms.Label
	Private button1 As System.Windows.Forms.Button
End Class
