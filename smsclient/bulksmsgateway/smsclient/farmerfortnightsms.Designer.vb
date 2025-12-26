'
' Created by SharpDevelop.
' User: admin
' Date: 12/01/2019
' Time: 12:51
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Partial Class farmerfortnightsms
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
		Me.btnExit = New System.Windows.Forms.Button()
		Me.richTextBox1 = New System.Windows.Forms.RichTextBox()
		Me.label1 = New System.Windows.Forms.Label()
		Me.label2 = New System.Windows.Forms.Label()
		Me.SuspendLayout
		'
		'btnExit
		'
		Me.btnExit.Location = New System.Drawing.Point(494, 12)
		Me.btnExit.Name = "btnExit"
		Me.btnExit.Size = New System.Drawing.Size(75, 23)
		Me.btnExit.TabIndex = 6
		Me.btnExit.Text = "Exit"
		Me.btnExit.UseVisualStyleBackColor = true
		AddHandler Me.btnExit.Click, AddressOf Me.BtnExitClick
		'
		'richTextBox1
		'
		Me.richTextBox1.Font = New System.Drawing.Font("Mangal", 12!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.richTextBox1.Location = New System.Drawing.Point(10, 41)
		Me.richTextBox1.Name = "richTextBox1"
		Me.richTextBox1.Size = New System.Drawing.Size(559, 200)
		Me.richTextBox1.TabIndex = 5
		Me.richTextBox1.Text = ""
		'
		'label1
		'
		Me.label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 12!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.label1.ForeColor = System.Drawing.Color.White
		Me.label1.Location = New System.Drawing.Point(-127, -5)
		Me.label1.Name = "label1"
		Me.label1.Size = New System.Drawing.Size(100, 23)
		Me.label1.TabIndex = 7
		Me.label1.Text = "SMS Details"
		'
		'label2
		'
		Me.label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 12!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.label2.ForeColor = System.Drawing.Color.White
		Me.label2.Location = New System.Drawing.Point(12, 9)
		Me.label2.Name = "label2"
		Me.label2.Size = New System.Drawing.Size(227, 23)
		Me.label2.TabIndex = 11
		Me.label2.Text = "Farmer Payment SMS Details"
		'
		'farmerfortnightsms
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.BackColor = System.Drawing.Color.Green
		Me.ClientSize = New System.Drawing.Size(581, 248)
		Me.Controls.Add(Me.label2)
		Me.Controls.Add(Me.btnExit)
		Me.Controls.Add(Me.richTextBox1)
		Me.Controls.Add(Me.label1)
		Me.Name = "farmerfortnightsms"
		Me.Text = "farmerfortnightsms"
		AddHandler Load, AddressOf Me.farmerfortnightLoad
		AddHandler Shown, AddressOf Me.farmerfortnightShown
		Me.ResumeLayout(false)
	End Sub
	Private label2 As System.Windows.Forms.Label
	Private label1 As System.Windows.Forms.Label
	Private richTextBox1 As System.Windows.Forms.RichTextBox
	Private btnExit As System.Windows.Forms.Button
End Class
