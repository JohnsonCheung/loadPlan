Attribute VB_Name = "M_Func"
Option Explicit
Public Const Ws1LastCol = "M"


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
