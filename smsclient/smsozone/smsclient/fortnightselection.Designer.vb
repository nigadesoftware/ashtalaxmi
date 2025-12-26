'
' Created by SharpDevelop.
' User: admin
' Date: 12/01/2019
' Time: 11:48
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Partial Class fortnightselection
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
		Me.groupBox1 = New System.Windows.Forms.GroupBox()
		Me.btnSubmit = New System.Windows.Forms.Button()
		Me.cboYear = New System.Windows.Forms.ComboBox()
		Me.label1 = New System.Windows.Forms.Label()
		Me.groupBox1.SuspendLayout
		Me.SuspendLayout
		'
		'groupBox1
		'
		Me.groupBox1.BackColor = System.Drawing.Color.Snow
		Me.groupBox1.Controls.Add(Me.btnSubmit)
		Me.groupBox1.Controls.Add(Me.cboYear)
		Me.groupBox1.Controls.Add(Me.label1)
		Me.groupBox1.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.groupBox1.Location = New System.Drawing.Point(12, 9)
		Me.groupBox1.Name = "groupBox1"
		Me.groupBox1.Size = New System.Drawing.Size(640, 222)
		Me.groupBox1.TabIndex = 0
		Me.groupBox1.TabStop = false
		Me.groupBox1.Text = "Payment Fortnight Selection"
		AddHandler Me.groupBox1.Enter, AddressOf Me.GroupBox1Enter
		'
		'btnSubmit
		'
		Me.btnSubmit.Location = New System.Drawing.Point(126, 171)
		Me.btnSubmit.Name = "btnSubmit"
		Me.btnSubmit.Size = New System.Drawing.Size(100, 31)
		Me.btnSubmit.TabIndex = 5
		Me.btnSubmit.Text = "Send SMS"
		Me.btnSubmit.UseVisualStyleBackColor = true
		AddHandler Me.btnSubmit.Click, AddressOf Me.BtnSubmitClick
		'
		'cboYear
		'
		Me.cboYear.Font = New System.Drawing.Font("Mangal", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.cboYear.FormattingEnabled = true
		Me.cboYear.Location = New System.Drawing.Point(126, 42)
		Me.cboYear.Name = "cboYear"
		Me.cboYear.Size = New System.Drawing.Size(508, 31)
		Me.cboYear.TabIndex = 0
		'
		'label1
		'
		Me.label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.label1.Location = New System.Drawing.Point(19, 42)
		Me.label1.Name = "label1"
		Me.label1.Size = New System.Drawing.Size(100, 23)
		Me.label1.TabIndex = 0
		Me.label1.Text = "हंगाम"
		'
		'fortnightselection
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.BackColor = System.Drawing.Color.Maroon
		Me.ClientSize = New System.Drawing.Size(664, 242)
		Me.Controls.Add(Me.groupBox1)
		Me.Name = "fortnightselection"
		Me.Text = "fortnightselection"
		Me.TopMost = true
		AddHandler Load, AddressOf Me.FortnightselectionLoad
		Me.groupBox1.ResumeLayout(false)
		Me.ResumeLayout(false)
	End Sub
	Private btnSubmit As System.Windows.Forms.Button
	Private label1 As System.Windows.Forms.Label
	Private cboYear As System.Windows.Forms.ComboBox
	Private groupBox1 As System.Windows.Forms.GroupBox
End Class
