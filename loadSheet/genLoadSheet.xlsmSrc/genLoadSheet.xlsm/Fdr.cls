VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Fdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Seg$
Public LoadSheet As LoadSheet
Public PthInp$
Function NewFdr(Seg$) As Fdr
A_Seg = Seg
PthInp = LSPth.Que & Seg & "\"
Set LoadSheet = NewLoadSheet(Seg)
Set NewFdr = Me
End Function

Sub Gen()
On Error GoTo X
LoadSheet.Gen
PthDlt PthInp, Force:=True
Exit Sub
X:
    MovToEr
End Sub

Function MovToEr$()
Dim A$, B$
A = LSPth.QueErr & TimStmp
MkDir A
A = A & "\" & A_Seg
B = PthMov(PthInp, A)       '<=== Move the PthInp to LSPth.QueErr
MovToEr = A
If B <> "" Then
    Dim M$()
    Push M, vbTab & "Cannot move folder: "
    Push M, vbTab & "  Fm=[" & PthInp & "]."
    Push M, vbTab & "  To=[" & A & "]."
    Push M, vbTab & "  ErMsg=[" & B & "]"
    Logr.WrtAy M
End If
End Function
