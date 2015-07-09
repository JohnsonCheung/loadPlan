Attribute VB_Name = "M_Func"
Option Explicit
Private A_Que As New Que
Public Const Ws1LastCol = "M"
Function SchNxtTick_TmrChkQue()
'it is required by [Que.SchNxtTick]
Que.SchNxtTick_TmrChkQue
End Function
Property Get Que() As Que
Set Que = A_Que
End Property

Property Get PthQue$()
Static O$
If O = "" Then O = PthCur & "Que\"
PthQue = O
End Property

Property Get PthCur()
Static O$
If O = "" Then O = FfnPth(ThisWorkbook.FullName)
PthCur = O
End Property

Property Get PthQueErr()
Static O$
If O = "" Then
    O = PthCur & "QueErr\"
    PthCrtEachSeg O
End If
PthQueErr = O
End Property

Function FfnContent$(YYMMDD$, OrdNo%, ContentNo%)
Dim YY$
Dim MM$
Dim DD$
YY = Left(YYMMDD, 2)
MM = Mid(YYMMDD, 3, 2)
DD = Mid(YYMMDD, 5, 2)
Dim Fn$, Ord$, Content$
Ord = Format(OrdNo, "0000")
Content = Format(ContentNo, "00")
Fn = "Ord-20" & YYMMDD & "-" & Ord & "-" & Content & ".png"
'ZFfnAtt = ZCurPth & "..\..\ordContent\20" & YY & "\20" & YY & "-" & MM & "\20" & YY & "-" & MM & "-" & DD & "\" & Ord & "\" & Fn
FfnContent = PthNorm(PthCur & "..\..\ordContent\20" & YY & "\" & MM & "\" & DD & "\" & Ord & "\" & Fn)
End Function
