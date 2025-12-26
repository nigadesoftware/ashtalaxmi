'
' Created by SharpDevelop.
' User: admin
' Date: 24/01/2019
' Time: 14:38
' 
' To change this template use Tools | Options | Coding | Edit Standard Headers.
'
Partial Class voucherdetail
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
		Me.btn_voucherdetailconvert = New System.Windows.Forms.Button()
		Me.lblTrans_no = New System.Windows.Forms.Label()
		Me.SuspendLayout
		'
		'btn_voucherdetailconvert
		'
		Me.btn_voucherdetailconvert.Location = New System.Drawing.Point(73, 12)
		Me.btn_voucherdetailconvert.Name = "btn_voucherdetailconvert"
		Me.btn_voucherdetailconvert.Size = New System.Drawing.Size(146, 23)
		Me.btn_voucherdetailconvert.TabIndex = 1
		Me.btn_voucherdetailconvert.Text = "Voucher Detail Convert"
		Me.btn_voucherdetailconvert.UseVisualStyleBackColor = true
		AddHandler Me.btn_voucherdetailconvert.Click, AddressOf Me.Btn_voucherdetailconvertClick
		'
		'lblTrans_no
		'
		Me.lblTrans_no.Location = New System.Drawing.Point(12, 51)
		Me.lblTrans_no.Name = "lblTrans_no"
		Me.lblTrans_no.Size = New System.Drawing.Size(247, 23)
		Me.lblTrans_no.TabIndex = 2
		Me.lblTrans_no.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'voucherdetail
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.ClientSize = New System.Drawing.Size(284, 262)
		Me.Controls.Add(Me.lblTrans_no)
		Me.Controls.Add(Me.btn_voucherdetailconvert)
		Me.Name = "voucherdetail"
		Me.Text = "voucherdetail"
		Me.ResumeLayout(false)
	End Sub
	Private lblTrans_no As System.Windows.Forms.Label
	Private btn_voucherdetailconvert As System.Windows.Forms.Button
End Class
