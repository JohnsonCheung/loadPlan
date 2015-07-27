Attribute VB_Name = "VbStr__Tst"
Option Explicit
Sub TstAll_VbStr()
Debug.Print "TstAll_VbStr ---------------------------------"
Fmt__Tst
StrSubStrCnt__Tst
End Sub

Private Sub Fmt__Tst()
Dim A$, Exp$
A = "A-{0}/B-{1}"
Exp = "A-1/B-2"
Debug.Assert (Fmt(A, 1, 2)) = Exp
Pass "Fmt__Tst"
End Sub

Private Sub StrSubStrCnt__Tst()
Dim A$
A = "lskdf\n\lskdf\nlskdfdf"
Debug.Assert StrSubStrCnt(A, "\n") = 2
End Sub

Private Sub IsSfx__Tst()
Debug.Assert IsSfx("ab", "aab")
Debug.Assert Not IsSfx("Ab", "aab")
Debug.Assert Not IsSfx("ba", "aab")
End Sub

Private Sub IsPfx__Tst()
Debug.Assert IsPfx("aa", "aab")
Debug.Assert Not IsPfx("Aa", "aab")
Debug.Assert Not IsPfx("ba", "aab")
End Sub

