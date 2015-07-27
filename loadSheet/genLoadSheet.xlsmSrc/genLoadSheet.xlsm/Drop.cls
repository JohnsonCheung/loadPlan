VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Drop"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Private DropLinAy() As DropLin

Property Get NDrop%()
NDrop = Sz(DropLinAy)
End Property

Function NewDrop(Seg$) As Drop
Dim U%
    Dim Ay$(), P$
    P = LSPth.Que & Seg & "\"
    Ay = PthAyFn(P, "Drop-??.txt")
    U = UB(Ay)

Erase DropLinAy
    Dim J%
    For J = 0 To U
        Dim M As DropLin
            Dim Fn$
            Fn = "drop-" & Format(J + 1, "00") & ".txt"
        PushObj DropLinAy, NewDropLin(Seg, Fn)
    Next
Set NewDrop = Me
End Function

Sub FillWs(Ws As Worksheet, ByRef ORno&)
Dim J%
ORno = 11
For J = 0 To NDrop - 1
    DropLinAy(J).FillWs Ws, ORno
    ORno = ORno + 1
Next
Dim C1, C2, R1, R2
Dim BdrRge As Range
    C1 = 1
    R1 = 10
    C2 = Ws1LastCol
    R2 = 10 + NDrop
    Set BdrRge = Ws.Range(Ws.Cells(R1, C1), Ws.Cells(R2, C2))
RgeSetBdrGrid BdrRge    '<== Border
End Sub

