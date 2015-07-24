VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Hdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public A_Dic As Dictionary
Public TripChiNm$
Public Driver$
Public DriverTy$
Public Member$
Public Leader$

Sub Init(Fdr$)
Dim F$
F = PthQue & Fdr & "Hdr.txt"
Set A_Dic = NewDy.ByFt(F).A_Dic
TripChiNm = A_Dic("TripChiNm")
Driver = A_Dic("Driver")
DriverTy = A_Dic("DriverTy")
Member = A_Dic("Member")
Leader = A_Dic("Leader")
End Sub

Sub BrwHdrFt()
FtBrw A_Ft
End Sub

Function FillWs(Ws As Worksheet) As Boolean
WbFillCellByDic Ws.Parent, A_Dic
End Function
