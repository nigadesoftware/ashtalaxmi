'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 11:51
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
		Me.lblCode = New System.Windows.Forms.Label()
		Me.lblNameUni = New System.Windows.Forms.Label()
		Me.btnConvert = New System.Windows.Forms.Button()
		Me.btnVoucherHeader = New System.Windows.Forms.Button()
		Me.SuspendLayout
		'
		'lblCode
		'
		Me.lblCode.Font = New System.Drawing.Font("Arial Narrow", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblCode.ForeColor = System.Drawing.Color.Black
		Me.lblCode.Location = New System.Drawing.Point(12, 9)
		Me.lblCode.Name = "lblCode"
		Me.lblCode.Size = New System.Drawing.Size(468, 21)
		Me.lblCode.TabIndex = 6
		Me.lblCode.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'lblNameUni
		'
		Me.lblNameUni.Font = New System.Drawing.Font("Arial Narrow", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblNameUni.ForeColor = System.Drawing.Color.Black
		Me.lblNameUni.Location = New System.Drawing.Point(12, 30)
		Me.lblNameUni.Name = "lblNameUni"
		Me.lblNameUni.Size = New System.Drawing.Size(468, 47)
		Me.lblNameUni.TabIndex = 5
		Me.lblNameUni.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'btnConvert
		'
		Me.btnConvert.Location = New System.Drawing.Point(76, 88)
		Me.btnConvert.Name = "btnConvert"
		Me.btnConvert.Size = New System.Drawing.Size(75, 23)
		Me.btnConvert.TabIndex = 7
		Me.btnConvert.Text = "Convert"
		Me.btnConvert.UseVisualStyleBackColor = true
		AddHandler Me.btnConvert.Click, AddressOf Me.BtnConvertClick
		'
		'btnVoucherHeader
		'
		Me.btnVoucherHeader.Location = New System.Drawing.Point(157, 88)
		Me.btnVoucherHeader.Name = "btnVoucherHeader"
		Me.btnVoucherHeader.Size = New System.Drawing.Size(102, 23)
		Me.btnVoucherHeader.TabIndex = 8
		Me.btnVoucherHeader.Text = "Voucher Header"
		Me.btnVoucherHeader.UseVisualStyleBackColor = true
		AddHandler Me.btnVoucherHeader.Click, AddressOf Me.BtnVoucherHeaderClick
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.BackColor = System.Drawing.Color.NavajoWhite
		Me.ClientSize = New System.Drawing.Size(492, 115)
		Me.Controls.Add(Me.btnVoucherHeader)
		Me.Controls.Add(Me.btnConvert)
		Me.Controls.Add(Me.lblCode)
		Me.Controls.Add(Me.lblNameUni)
		Me.Name = "MainForm"
		Me.Text = "dataconversion"
		Me.ResumeLayout(false)
	End Sub
	Private btnVoucherHeader As System.Windows.Forms.Button
	Private btnConvert As System.Windows.Forms.Button
	Private lblNameUni As System.Windows.Forms.Label
	Private lblCode As System.Windows.Forms.Label
End Class
