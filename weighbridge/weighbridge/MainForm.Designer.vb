'
' Created by SharpDevelop.
' User: admin
' Date: 05/06/2019
' Time: 10:40
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
		Me.components = New System.ComponentModel.Container()
		Me.button1 = New System.Windows.Forms.Button()
		Me.timer1 = New System.Windows.Forms.Timer(Me.components)
		Me.txtWeight = New System.Windows.Forms.TextBox()
		Me.label1 = New System.Windows.Forms.Label()
		Me.lblKataNumber = New System.Windows.Forms.Label()
		Me.lblMacAddress = New System.Windows.Forms.Label()
		Me.SuspendLayout
		'
		'button1
		'
		Me.button1.Location = New System.Drawing.Point(99, 132)
		Me.button1.Name = "button1"
		Me.button1.Size = New System.Drawing.Size(75, 23)
		Me.button1.TabIndex = 0
		Me.button1.Text = "button1"
		Me.button1.UseVisualStyleBackColor = true
		Me.button1.Visible = false
		AddHandler Me.button1.Click, AddressOf Me.Button1Click
		'
		'timer1
		'
		Me.timer1.Interval = 500
		AddHandler Me.timer1.Tick, AddressOf Me.Timer1Tick
		'
		'txtWeight
		'
		Me.txtWeight.Enabled = false
		Me.txtWeight.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.txtWeight.Location = New System.Drawing.Point(13, 53)
		Me.txtWeight.Name = "txtWeight"
		Me.txtWeight.Size = New System.Drawing.Size(252, 38)
		Me.txtWeight.TabIndex = 1
		'
		'label1
		'
		Me.label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 15.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.label1.Location = New System.Drawing.Point(13, 13)
		Me.label1.Name = "label1"
		Me.label1.Size = New System.Drawing.Size(125, 37)
		Me.label1.TabIndex = 2
		Me.label1.Text = "Kata No :"
		'
		'lblKataNumber
		'
		Me.lblKataNumber.Font = New System.Drawing.Font("Microsoft Sans Serif", 15.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblKataNumber.Location = New System.Drawing.Point(125, 13)
		Me.lblKataNumber.Name = "lblKataNumber"
		Me.lblKataNumber.Size = New System.Drawing.Size(125, 37)
		Me.lblKataNumber.TabIndex = 3
		'
		'lblMacAddress
		'
		Me.lblMacAddress.Location = New System.Drawing.Point(13, 98)
		Me.lblMacAddress.Name = "lblMacAddress"
		Me.lblMacAddress.Size = New System.Drawing.Size(252, 23)
		Me.lblMacAddress.TabIndex = 4
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.ClientSize = New System.Drawing.Size(277, 124)
		Me.ControlBox = false
		Me.Controls.Add(Me.lblMacAddress)
		Me.Controls.Add(Me.lblKataNumber)
		Me.Controls.Add(Me.label1)
		Me.Controls.Add(Me.txtWeight)
		Me.Controls.Add(Me.button1)
		Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
		Me.Name = "MainForm"
		Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
		Me.TopMost = true
		Me.WindowState = System.Windows.Forms.FormWindowState.Minimized
		AddHandler Load, AddressOf Me.MainFormLoad
		Me.ResumeLayout(false)
		Me.PerformLayout
	End Sub
	Private lblMacAddress As System.Windows.Forms.Label
	Private lblKataNumber As System.Windows.Forms.Label
	Private label1 As System.Windows.Forms.Label
	Private txtWeight As System.Windows.Forms.TextBox
	Private timer1 As System.Windows.Forms.Timer
	Private button1 As System.Windows.Forms.Button
End Class
