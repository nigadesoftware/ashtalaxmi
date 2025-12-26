'
' Created by SharpDevelop.
' User: admin
' Date: 26/12/2018
' Time: 18:32
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
		Me.btnFarmer = New System.Windows.Forms.Button()
		Me.lblNameUni = New System.Windows.Forms.Label()
		Me.btnVillage = New System.Windows.Forms.Button()
		Me.lblCode = New System.Windows.Forms.Label()
		Me.btnExit = New System.Windows.Forms.Button()
		Me.btnSection = New System.Windows.Forms.Button()
		Me.btnBankBranch = New System.Windows.Forms.Button()
		Me.btnSociety = New System.Windows.Forms.Button()
		Me.btnBillPeriod = New System.Windows.Forms.Button()
		Me.timer1 = New System.Windows.Forms.Timer(Me.components)
		Me.btnTransporter = New System.Windows.Forms.Button()
		Me.btnHarvester = New System.Windows.Forms.Button()
		Me.btnBullockcart = New System.Windows.Forms.Button()
		Me.SuspendLayout
		'
		'btnFarmer
		'
		Me.btnFarmer.Location = New System.Drawing.Point(48, 82)
		Me.btnFarmer.Name = "btnFarmer"
		Me.btnFarmer.Size = New System.Drawing.Size(133, 23)
		Me.btnFarmer.TabIndex = 0
		Me.btnFarmer.Text = "Farmer"
		Me.btnFarmer.UseVisualStyleBackColor = true
		Me.btnFarmer.Visible = false
		AddHandler Me.btnFarmer.Click, AddressOf Me.BtnFarmerClick
		'
		'lblNameUni
		'
		Me.lblNameUni.Font = New System.Drawing.Font("Arial Narrow", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblNameUni.ForeColor = System.Drawing.Color.Yellow
		Me.lblNameUni.Location = New System.Drawing.Point(12, 26)
		Me.lblNameUni.Name = "lblNameUni"
		Me.lblNameUni.Size = New System.Drawing.Size(260, 21)
		Me.lblNameUni.TabIndex = 2
		Me.lblNameUni.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'btnVillage
		'
		Me.btnVillage.Location = New System.Drawing.Point(187, 82)
		Me.btnVillage.Name = "btnVillage"
		Me.btnVillage.Size = New System.Drawing.Size(133, 23)
		Me.btnVillage.TabIndex = 3
		Me.btnVillage.Text = "Village"
		Me.btnVillage.UseVisualStyleBackColor = true
		Me.btnVillage.Visible = false
		AddHandler Me.btnVillage.Click, AddressOf Me.BtnVillageClick
		'
		'lblCode
		'
		Me.lblCode.Font = New System.Drawing.Font("Arial Narrow", 14.25!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0,Byte))
		Me.lblCode.ForeColor = System.Drawing.Color.Yellow
		Me.lblCode.Location = New System.Drawing.Point(12, 5)
		Me.lblCode.Name = "lblCode"
		Me.lblCode.Size = New System.Drawing.Size(260, 21)
		Me.lblCode.TabIndex = 4
		Me.lblCode.TextAlign = System.Drawing.ContentAlignment.MiddleCenter
		'
		'btnExit
		'
		Me.btnExit.DialogResult = System.Windows.Forms.DialogResult.Cancel
		Me.btnExit.Location = New System.Drawing.Point(70, 53)
		Me.btnExit.Name = "btnExit"
		Me.btnExit.Size = New System.Drawing.Size(133, 23)
		Me.btnExit.TabIndex = 5
		Me.btnExit.Text = "Exit"
		Me.btnExit.UseVisualStyleBackColor = true
		AddHandler Me.btnExit.Click, AddressOf Me.BtnExitClick
		'
		'btnSection
		'
		Me.btnSection.Location = New System.Drawing.Point(187, 111)
		Me.btnSection.Name = "btnSection"
		Me.btnSection.Size = New System.Drawing.Size(133, 23)
		Me.btnSection.TabIndex = 6
		Me.btnSection.Text = "Section"
		Me.btnSection.UseVisualStyleBackColor = true
		Me.btnSection.Visible = false
		AddHandler Me.btnSection.Click, AddressOf Me.BtnSectionClick
		'
		'btnBankBranch
		'
		Me.btnBankBranch.Location = New System.Drawing.Point(187, 140)
		Me.btnBankBranch.Name = "btnBankBranch"
		Me.btnBankBranch.Size = New System.Drawing.Size(133, 23)
		Me.btnBankBranch.TabIndex = 7
		Me.btnBankBranch.Text = "Bank Branch"
		Me.btnBankBranch.UseVisualStyleBackColor = true
		Me.btnBankBranch.Visible = false
		AddHandler Me.btnBankBranch.Click, AddressOf Me.BtnBankBranchClick
		'
		'btnSociety
		'
		Me.btnSociety.Location = New System.Drawing.Point(187, 169)
		Me.btnSociety.Name = "btnSociety"
		Me.btnSociety.Size = New System.Drawing.Size(133, 23)
		Me.btnSociety.TabIndex = 8
		Me.btnSociety.Text = "Society"
		Me.btnSociety.UseVisualStyleBackColor = true
		Me.btnSociety.Visible = false
		AddHandler Me.btnSociety.Click, AddressOf Me.BtnSocietyClick
		'
		'btnBillPeriod
		'
		Me.btnBillPeriod.Location = New System.Drawing.Point(187, 198)
		Me.btnBillPeriod.Name = "btnBillPeriod"
		Me.btnBillPeriod.Size = New System.Drawing.Size(133, 23)
		Me.btnBillPeriod.TabIndex = 9
		Me.btnBillPeriod.Text = "Bill Period"
		Me.btnBillPeriod.UseVisualStyleBackColor = true
		Me.btnBillPeriod.Visible = false
		AddHandler Me.btnBillPeriod.Click, AddressOf Me.BtnBillPeriodClick
		'
		'timer1
		'
		Me.timer1.Interval = 30000
		AddHandler Me.timer1.Tick, AddressOf Me.Timer1Tick
		'
		'btnTransporter
		'
		Me.btnTransporter.Location = New System.Drawing.Point(59, 111)
		Me.btnTransporter.Name = "btnTransporter"
		Me.btnTransporter.Size = New System.Drawing.Size(75, 23)
		Me.btnTransporter.TabIndex = 10
		Me.btnTransporter.Text = "Transporter"
		Me.btnTransporter.UseVisualStyleBackColor = true
		Me.btnTransporter.Visible = false
		AddHandler Me.btnTransporter.Click, AddressOf Me.BtnTransporterClick
		'
		'btnHarvester
		'
		Me.btnHarvester.Location = New System.Drawing.Point(59, 140)
		Me.btnHarvester.Name = "btnHarvester"
		Me.btnHarvester.Size = New System.Drawing.Size(75, 23)
		Me.btnHarvester.TabIndex = 11
		Me.btnHarvester.Text = "Harvester"
		Me.btnHarvester.UseVisualStyleBackColor = true
		AddHandler Me.btnHarvester.Click, AddressOf Me.BtnHarvesterClick
		'
		'btnBullockcart
		'
		Me.btnBullockcart.Location = New System.Drawing.Point(59, 169)
		Me.btnBullockcart.Name = "btnBullockcart"
		Me.btnBullockcart.Size = New System.Drawing.Size(75, 23)
		Me.btnBullockcart.TabIndex = 12
		Me.btnBullockcart.Text = "Bullock Cart"
		Me.btnBullockcart.UseVisualStyleBackColor = true
		AddHandler Me.btnBullockcart.Click, AddressOf Me.BtnBullockcartClick
		'
		'MainForm
		'
		Me.AutoScaleDimensions = New System.Drawing.SizeF(6!, 13!)
		Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
		Me.BackColor = System.Drawing.Color.Maroon
		Me.CancelButton = Me.btnExit
		Me.ClientSize = New System.Drawing.Size(284, 80)
		Me.ControlBox = false
		Me.Controls.Add(Me.btnBullockcart)
		Me.Controls.Add(Me.btnHarvester)
		Me.Controls.Add(Me.btnTransporter)
		Me.Controls.Add(Me.btnBillPeriod)
		Me.Controls.Add(Me.btnSociety)
		Me.Controls.Add(Me.btnBankBranch)
		Me.Controls.Add(Me.btnSection)
		Me.Controls.Add(Me.btnExit)
		Me.Controls.Add(Me.lblCode)
		Me.Controls.Add(Me.btnVillage)
		Me.Controls.Add(Me.lblNameUni)
		Me.Controls.Add(Me.btnFarmer)
		Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.Fixed3D
		Me.Name = "MainForm"
		Me.StartPosition = System.Windows.Forms.FormStartPosition.Manual
		Me.Text = "Sync Unicode Data"
		AddHandler Load, AddressOf Me.MainFormLoad
		Me.ResumeLayout(false)
	End Sub
	Private btnBullockcart As System.Windows.Forms.Button
	Private btnHarvester As System.Windows.Forms.Button
	Private btnTransporter As System.Windows.Forms.Button
	Private timer1 As System.Windows.Forms.Timer
	Private btnBillPeriod As System.Windows.Forms.Button
	Private btnSociety As System.Windows.Forms.Button
	Private btnBankBranch As System.Windows.Forms.Button
	Private btnSection As System.Windows.Forms.Button
	Private btnExit As System.Windows.Forms.Button
	Private lblCode As System.Windows.Forms.Label
	Private btnVillage As System.Windows.Forms.Button
	Private lblNameUni As System.Windows.Forms.Label
	Private btnFarmer As System.Windows.Forms.Button
End Class
