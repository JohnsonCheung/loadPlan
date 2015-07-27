Attribute VB_Name = "Dy__Tst"
Option Explicit

Sub Assign__Tst()
Dim Dy As Dy
Set Dy = NewDy.ByLpAp("A B C D", 1, 2, 3, 4)
Dim A$, B%, C#, D!
Dy.Assign "D C B A", D, C, B, A
Debug.Assert A = "1"
Debug.Assert B = 2
Debug.Assert C = 3#
Debug.Assert D = 4!
Pass "Assign__Tst"
End Sub

Sub ReplBackSlash_ByCrLf__Tst()
Dim Dy As Dy
Set Dy = NewDy.ByLpAp("A B C D", "1\n1", "2\n2", "3", "4\n4")
Dy.ReplBackSlashN_ByCrLf

Debug.Assert Dy.Cnt = 4
Debug.Assert Dy("A") = "1" & vbCrLf & "1"
Debug.Assert Dy("B") = "2" & vbCrLf & "2"
Debug.Assert Dy("C") = "3"
Debug.Assert Dy("D") = "4" & vbCrLf & "4"
Pass "ReplBackSlash_ByCrLf__Tst"
End Sub

