VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "FillDrops"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private DropAy() As FillDrop
Public NDrop%
Sub Init(Fdr$)
Erase DropAy
Dim DropFnAy$(), P$, M As FillDrop
P = PthQue & Fdr & "\"
DropFnAy = PthAyFn(P, "Drop-??.txt")
Dim J%, A$
If UB(DropFnAy) >= 0 Then
    ReDim O(UB(DropFnAy))
    For J = 0 To UB(DropFnAy)
        A = "drop-" & Format(J + 1, "00") & ".txt"
        If Not IsSfx(A, DropFnAy(J)) Then WrtLog "Warning: DropFnAy(J)=[" & DropFnAy(J) & "] not expected.  Expected Sfx=[" & A & "]!!"
        Set M = New FillDrop
        M.Init Fdr, DropFnAy(J)
        PushObj DropAy, M
    Next
End If
NDrop = Sz(DropAy)
End Sub

Function Fillin(Ws As Worksheet, ByRef ORno%) As Boolean
Dim J%, CnoAy%(), N%
N = NDrop
CnoAy = GetCnoAy(Ws)
ORno = 11
For J = 0 To N - 1
    If DropAy(J).Fillin(Ws, ORno, CnoAy) Then Fillin = True '<-- FillDrop
    ORno = ORno + 1
Next
Dim C1, C2, R1, R2
Dim BdrRge As Range
    C1 = 1
    R1 = 11
    C2 = Ws1LastCol
    R2 = 11 + N - 1
    Set BdrRge = Ws.Range(Ws.Cells(R1, C1), Ws.Cells(R2, C2))
RgeSetBdrGrid BdrRge    '<== Border
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
