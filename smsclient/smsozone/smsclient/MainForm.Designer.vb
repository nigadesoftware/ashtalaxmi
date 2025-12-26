'
' Created by SharpDevelop.
' User: admin
' Date: 08/01/2019
' Time: 15:47
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
		Me.richTextBox1 = New System.Windows.Forms.RichTextBox()
		Me.btnExit = New System.Windows.Forms.Button()
		Me.label1 = New System.Windows.Forms.Label()
		Me.btnFarmerPayment = New System.Windows.Forms.Button()
		Me.SuspendLayout
		'
		'button1
		'
		Me.button1.Cursor = System.Windows.Forms.Cursors.No
		Me.button1.Location = New System.Drawing.Point(427, 2)
		Me.button1.Name = "button1"
		Me.button1.Size = New System.Drawing.Size(134, 23)
		Me.button1.TabIndex = 12
		Me.button1.TabStop = false
		Me.button1.Text = "Test Message Send"
		Me.button1.UseVisualStyleBackColor = true
		AddHandler Me.button1.Click, AddressOf Me.Button1Click
		'
		'timer1
		'
		Me.timer1.Interval = 60000
		AddHandler Me.timer1.Tick, AddressOf Me.Timer1Tick
		'
		'richTextBox1
		'
		Me.richTextBox1.Font = New System.Drawing.Font("Nirmala UI", 12!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.richTextBox1.Location = New System.Drawing.Point(2, 28)
		Me.richTextBox1.Name = "richTextBox1"
		Me.richTextBox1.Size = New System.Drawing.Size(559, 217)
		Me.richTextBox1.TabIndex = 1
		Me.richTextBox1.Text = ""
		'
		'btnExit
		'
		Me.btnExit.Location = New System.Drawing.Point(263, 251)
		Me.btnExit.Name = "btnExit"
		Me.btnExit.Size = New System.Drawing.Size(75, 30)
		Me.btnExit.TabIndex = 2
		Me.btnExit.Text = "Exit"
		Me.btnExit.UseVisualStyleBackColor = true
		AddHandler Me.btnExit.Click, AddressOf Me.BtnExitClick
		'
		'label1
		'
		Me.label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 12!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.label1.ForeColor = System.Drawing.Color.White
		Me.label1.Location = New System.Drawing.Point(12, 2)
		Me.label1.Name = "label1"
		Me.label1.Size = New System.Drawing.Size(100, 23)
		Me.label1.TabIndex = 3
		Me.label1.Text = "SMS Details"
		'
		'btnFarmerPayment
		'
		Me.btnFarmerPayment.Location = New System.Drawing.Point(141, 251)
		Me.btnFarmerPayment.Name = "btnFarmerPayment"
		Me.btnFarmerPayment.Size = New System.Drawing.Size(95, 30)
		Me.btnFarmerPayment.TabIndex = 4
		Me.btnFarmerPayment.Text = "Farmer Payment"
		Me.btnFarmerPayment.UseVisualStyleBackColor = true
		AddHandler Me.btnFarmerPayment.Click, AddressOf Me.BtnFarmerPaymentClick
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.BackColor = System.Drawing.Color.IndianRed
		Me.ClientSize = New System.Drawing.Size(573, 286)
		Me.ControlBox = false
		Me.Controls.Add(Me.btnFarmerPayment)
		Me.Controls.Add(Me.label1)
		Me.Controls.Add(Me.btnExit)
		Me.Controls.Add(Me.richTextBox1)
		Me.Controls.Add(Me.button1)
		Me.Name = "MainForm"
		Me.Text = "nigadeERP - SMS"
		AddHandler Load, AddressOf Me.MainFormLoad
		AddHandler Shown, AddressOf Me.MainFormShown
		Me.ResumeLayout(false)
	End Sub
	Private btnFarmerPayment As System.Windows.Forms.Button
	Private label1 As System.Windows.Forms.Label
	Private btnExit As System.Windows.Forms.Button
	Private richTextBox1 As System.Windows.Forms.RichTextBox
	Private timer1 As System.Windows.Forms.Timer
	Private button1 As System.Windows.Forms.Button
End Class
