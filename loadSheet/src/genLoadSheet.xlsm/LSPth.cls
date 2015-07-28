VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "LSPth"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit

Property Get Que$()
Static O$
If O = "" Then O = Hom & "Que\"
Que = O
End Property

Property Get Hom()
Static O$
If O = "" Then O = FfnPth(ThisWorkbook.FullName)
Hom = O
End Property

Property Get QueErr()
Static O$
If O = "" Then
    O = PthCur & "QueErr\"
    PthCrtEachSeg O
End If
QueErr = O
End Property

Property Get QueTst$()
Static O$
If O = "" Then O = PthCur & "QueTst\"
QueTst = O
End Property

Function Oup$(YY%, MM%, DD%)
Dim A$
    A = PthNorm(PthCur & "..\..\loadSheet\")
Oup = A & YY & "\" & Format(MM, "00") & "\" & Format(DD, "00") & "\"
End Function
