VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Pth"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit

Property Get Que$()
Static O$
If O = "" Then O = Cur & "Que\"
Que = O
End Property

Property Get Cur()
Static O$
If O = "" Then O = FfnPth(ThisWorkbook.FullName)
Cur = O
End Property

Property Get QueErr()
Static O$
If O = "" Then
    O = PthCur & "QueErr\"
    PthCrtEachSeg O
End If
QueErr = O
End Property
