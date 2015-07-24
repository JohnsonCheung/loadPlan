Attribute VB_Name = "modLib__Test"
Option Explicit

Private Sub StrSubStrCnt__Tst()
Dim A$
A = "lskdf\n\lskdf\nlskdfdf"
Debug.Assert StrSubStrCnt(A, "\n") = 2
End Sub


Private Sub WsFillCellByDic__Tst()
Dim Ws As Worksheet, Dy As New Dy
WsFillCellByDic Ws, Dy.A_Dic


End Sub

Private Sub WsFillRowByDic__Tst()

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

Sub PthAyFnLik__Tst()
Dim Act$()
Act = PthAyFnLik("C:\", "Pytho##")
Debug.Assert Sz(Act) = 2
Debug.Assert Act(0) = "Python27"
Debug.Assert Act(0) = "Python33"
End Sub

Sub FtBrw_Tst()
Open "C:\Temp\a.txt" For Output As #1
Print #1, "asdfsdf"
Print #1, "lsdkfjsdlf"
Close #1
FtBrw "C:\Temp\a.txt"
End Sub

