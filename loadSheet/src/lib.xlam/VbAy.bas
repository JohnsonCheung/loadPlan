Attribute VB_Name = "VbAy"
Option Explicit

Function AyIsEq(A1, A2) As Boolean
Dim N&, J&
N = Sz(A1)
If N <> Sz(A2) Then Exit Function
For J = 0 To N - 1
    If A1(J) <> A2(J) Then Exit Function
Next
AyIsEq = True
End Function

Sub PushObj(Ay, Obj As Object)
Dim N&
N = Sz(Ay)
ReDim Preserve Ay(N)
Set Ay(N) = Obj
End Sub

Function UB&(Ay)
UB = Sz(Ay) - 1
End Function

Sub AyDmp(Ay)
Dim J&
For J = 0 To UB(Ay)
    Debug.Print J & ":[" & Ay(J) & "]"
Next
End Sub

Function AyLasEle(Ay)
AyLasEle = Ay(UB(Ay))
End Function

Sub Push(Ay, I)
Dim N&
N = Sz(Ay)
ReDim Preserve Ay(N)
Ay(N) = I
End Sub

Function Sz&(Ay)
On Error Resume Next
Sz = UBound(Ay) + 1
End Function


