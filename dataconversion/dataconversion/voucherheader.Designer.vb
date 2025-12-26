'
' Created by SharpDevelop.
' User: admin
' Date: 18/01/2019
' Time: 18:48
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Partial Class voucherheader
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
		Me.btn_voucherheaderconvert = New System.Windows.Forms.Button()
		Me.lblTrans_no = New System.Windows.Forms.Label()
		Me.lblNarration = New System.Windows.Forms.Label()
		Me.lblDescription = New System.Windows.Forms.Label()
		Me.btn_voucherdetailconvert = New System.Windows.Forms.Button()
		Me.SuspendLayout
		'
		'btn_voucherheaderconvert
		'
		Me.btn_voucherheaderconvert.Location = New System.Drawing.Point(50, 24)
		Me.btn_voucherheaderconvert.Name = "btn_voucherheaderconvert"
		Me.btn_voucherheaderconvert.Size = New System.Drawing.Size(146, 23)
		Me.btn_voucherheaderconvert.TabIndex = 0
		Me.btn_voucherheaderconvert.Text = "Voucher Header Convert"
		Me.btn_voucherheaderconvert.UseVisualStyleBackColor = true
		AddHandler Me.btn_voucherheaderconvert.Click, AddressOf Me.Btn_voucherheaderconvertClick
		'
		'lblTrans_no
		'
		Me.lblTrans_no.Location = New System.Drawing.Point(12, 67)
		Me.lblTrans_no.Name = "lblTrans_no"
		Me.lblTrans_no.Size = New System.Drawing.Size(247, 23)
		Me.lblTrans_no.TabIndex = 1
		Me.lblTrans_no.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'lblNarration
		'
		Me.lblNarration.Location = New System.Drawing.Point(12, 94)
		Me.lblNarration.Name = "lblNarration"
		Me.lblNarration.Size = New System.Drawing.Size(247, 84)
		Me.lblNarration.TabIndex = 2
		'
		'lblDescription
		'
		Me.lblDescription.Location = New System.Drawing.Point(12, 200)
		Me.lblDescription.Name = "lblDescription"
		Me.lblDescription.Size = New System.Drawing.Size(247, 53)
		Me.lblDescription.TabIndex = 3
		'
		'btn_voucherdetailconvert
		'
		Me.btn_voucherdetailconvert.Location = New System.Drawing.Point(59, 265)
		Me.btn_voucherdetailconvert.Name = "btn_voucherdetailconvert"
		Me.btn_voucherdetailconvert.Size = New System.Drawing.Size(146, 23)
		Me.btn_voucherdetailconvert.TabIndex = 4
		Me.btn_voucherdetailconvert.Text = "Voucher Detail Convert"
		Me.btn_voucherdetailconvert.UseVisualStyleBackColor = true
		Me.btn_voucherdetailconvert.Visible = false
		AddHandler Me.btn_voucherdetailconvert.Click, AddressOf Me.Btn_voucherdetailconvertClick
		'
		'voucherheader
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.ClientSize = New System.Drawing.Size(284, 300)
		Me.Controls.Add(Me.btn_voucherdetailconvert)
		Me.Controls.Add(Me.lblDescription)
		Me.Controls.Add(Me.lblNarration)
		Me.Controls.Add(Me.lblTrans_no)
		Me.Controls.Add(Me.btn_voucherheaderconvert)
		Me.Name = "voucherheader"
		Me.Text = "voucherheader"
		Me.ResumeLayout(false)
	End Sub
	Private btn_voucherdetailconvert As System.Windows.Forms.Button
	Private lblDescription As System.Windows.Forms.Label
	Private lblNarration As System.Windows.Forms.Label
	Private lblTrans_no As System.Windows.Forms.Label
	Private btn_voucherheaderconvert As System.Windows.Forms.Button
End Class
