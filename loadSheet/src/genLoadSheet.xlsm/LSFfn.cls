VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "LSFfn"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Function Content$(YYMMDD$, OrdNo%, ContentNo%)
Dim YY$
Dim MM$
Dim DD$
YY = Left(YYMMDD, 2)
MM = Mid(YYMMDD, 3, 2)
DD = Mid(YYMMDD, 5, 2)
Dim Fn$, Ord$, mContent$
Ord = Format(OrdNo, "0000")
mContent = Format(ContentNo, "00")
Fn = "Ord-20" & YYMMDD & "-" & Ord & "-" & mContent & ".png"
'ZFfnAtt = ZCurPth & "..\..\ordContent\20" & YY & "\20" & YY & "-" & MM & "\20" & YY & "-" & MM & "-" & DD & "\" & Ord & "\" & Fn
Content = PthNorm(PthCur & "..\..\ordContent\20" & YY & "\" & MM & "\" & DD & "\" & Ord & "\" & Fn)
End Function

