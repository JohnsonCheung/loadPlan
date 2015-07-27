VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Logr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Sub WrtAy(Ay$())
Dim F%
F = FreeFile(1)
Open Logr.Ft For Append As #F
Dim J%
For J = 0 To UB(Ay)
    Print #F, Ay(J)
Next
Close #F
End Sub

Sub Brw()
FtBrw Ft
End Sub

Sub WrtMsg(Msg$)
Dim F%
F = FreeFile(1)
Open Ft For Append As #F
Print #F, Msg
Close #F
End Sub

Property Get Ft$()
Dim Pth$
Dim Fn$
Pth = PthCur & "Log\" & Format(Date, "YYYY") & "\" & Format(Date, "MM") & "\"
PthCrtEachSeg Pth
Fn = "LoadSheetProcess-" & Format(Date, "YYYY-MM-DD") & ".log"
Ft = Pth & Fn
End Property

