VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "DropLin"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public OrdNo As String
Public Cus As String
Public Adr As String
Public Content As String
Public Qty As String
Public PagList As String
Public OrdBy As String
Public Contact As String
Public RmkOrdNo As String
Public RmkAdrNo As String
Sub Init(Fdr$, Fn$)
Dim F$
    F = PthQue & Fdr & "\" & Fn

Dim Dy As Dy
    Set Dy = NewDy.ByFt(Ft)
    Dy.Assign "OrdNo Cus Adr Content Qty PagList OrdBy Contact RmkOrdNo RmkAdrNo", OrdNo, Cus, Adr, Content, Qty, PagList, OrdBy, Contact, RmkOrdNo, RmkAdrNo

Exit Sub
X:
ClsTextStream S
WrtLog vbTab & "Cannot read drop-??.txt  File name=[" & Ft & "].  ErMsg=[" & Err.Description & "]"
End Sub
Function FillWs(Ws As Worksheet, Rno%) As Boolean
Dim RgeAy(8) As Range, J%
Dim CnoAy%()
    CnoAy = GetCnoAy(Ws)
    
For J = 0 To UB(CnoAy)
    Set RgeAy(J) = Ws.Cells(Rno, CnoAy(J))
Next
RgeAy(0).Value = Replace(OrdNo, "\n", vbCrLf)
RgeAy(1).Value = Replace(Cus, "\n", vbCrLf)
RgeAy(2).Value = Replace(Adr, "\n", vbCrLf)
RgeAy(3).Value = Replace(Content, "\n", vbCrLf)
RgeAy(4).Value = Replace(Qty, "\n", vbCrLf)
RgeAy(5).Value = Replace(PagList, "\n", vbCrLf)
RgeAy(6).Value = Replace(OrdBy, "\n", vbCrLf)
RgeAy(7).Value = Replace(Contact, "\n", vbCrLf)
RgeAy(8).Value = Replace(RmkNo, "\n", vbCrLf)
End Function


Private Function GetCnoAy(Ws As Worksheet) As Integer()
Dim O%(), Cno%, Wb As Workbook
Dim J%
Set Wb = Ws.Parent
Const L_OrdNo = "L_OrdNo"
Const L_Cus = "L_Cus"
Const L_Adr = "L_Adr"
Const L_Content = "L_Content"
Const L_Qty = "L_Qty"
Const L_PagList = "L_PagList"
Const L_OrdBy = "L_OrdBy"
Const L_Contact = "L_Contact"
Const L_RmkNo = "L_RmkNo"

Dim Ay$()
Push Ay, L_OrdNo
Push Ay, L_Cus
Push Ay, L_Adr
Push Ay, L_Content
Push Ay, L_Qty
Push Ay, L_PagList
Push Ay, L_OrdBy
Push Ay, L_Contact
Push Ay, L_RmkNo
ReDim O(UBound(Ay))
For J = 0 To UBound(Ay)
    O(J) = Wb.Names(Ay(J)).RefersToRange.Column
Next
GetCnoAy = O
End Function
