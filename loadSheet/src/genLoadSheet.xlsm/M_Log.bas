Attribute VB_Name = "M_Log"
Option Explicit

Sub WrtLogAy(Ay$())
Dim F%
F = FreeFile(1)
Open FtLog For Append As #F
Dim J%
For J = 0 To UB(Ay)
    Print #F, Ay(J)
Next
Close #F
End Sub

Sub BrwLog()
FtBrw FtLog
End Sub

Sub WrtLog(Msg$)
Dim F%
F = FreeFile(1)
Open FtLog For Append As #F
Print #F, Msg
Close #F
End Sub

Property Get FtLog$()
Dim Pth$
Dim Fn$
Pth = PthCur & "Log\" & Format(Date, "YYYY") & "\" & Format(Date, "MM") & "\"
PthCrtEachSeg Pth
Fn = "LoadSheetProcess-" & Format(Date, "YYYY-MM-DD") & ".log"
FtLog = Pth & Fn
End Property
