Attribute VB_Name = "Log__Tst"
Option Explicit
Sub TstAll_Log()
Ft__Tst
Brw__Tst
WrtMsg__Tst
WrtAy__Tst
End Sub

Private Sub Ft__Tst()
Dim D$, M$
    D = Format(Date, "YYYY-MM-DD")
    M = Format(Date, "MM")
Dim Exp$
    Exp = Fmt("C:\xampp\htdocs\loadPlan\pgm\loadSheet\Log\2015\{0}\LoadSheetProcess-{1}.log", M, D)
Debug.Assert Logr.Ft = Exp
End Sub

Private Sub Brw__Tst()
Logr.Brw
End Sub

Private Sub WrtMsg__Tst()
Dim A$, Ay$()
A = "AAA " & Now
Logr.WrtMsg A
Ay = FtAy(Logr.Ft)
Debug.Assert Ay(Sz(Ay) - 2) = A ' note:-2 instead of -1, becuase the last line is empty.
End Sub

Private Sub WrtAy__Tst()
Dim A$(2)
A(0) = 1 & " " & Now
A(1) = 2 & " " & Now
A(2) = 3 & " " & Now
Logr.WrtAy A
Dim Ay$()
Ay = FtAy(Logr.Ft)
Debug.Assert Ay(Sz(Ay) - 4) = A(0)
Debug.Assert Ay(Sz(Ay) - 3) = A(1)
Debug.Assert Ay(Sz(Ay) - 2) = A(2)
End Sub
