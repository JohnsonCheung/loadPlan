Attribute VB_Name = "VbPth"
Option Explicit


Function PthAyFdr(Pth$, Optional FSpec$ = "*.*") As String()
Dim O$(), M$
M = Dir(Pth & FSpec, vbDirectory)
While M <> ""
    If M <> "." Then
        If M <> ".." Then
            If IsPth(Pth & M) Then
                Push O, M
            End If
        End If
    End If
    M = Dir
Wend
PthAyFdr = O
End Function

Function PthAyFn(Pth, Optional FSpec$ = "*.*") As String()
Dim O$(), M$
M = Dir(Pth & FSpec)
While M <> ""
    Push O, M
    M = Dir
Wend
PthAyFn = O
End Function

Function PthMov$(Pth$, PthTo$)
On Error GoTo X
Dim A$, B$
A = RmvSlashAtEnd(Pth)
B = RmvSlashAtEnd(PthTo)
Fso.MoveFolder A, B
Exit Function
X: PthMov = Err.Description
End Function

Function IsPth(Pth$) As Boolean
IsPth = Fso.FolderExists(Pth)
End Function


Sub PthDlt(Pth$, Optional Force As Boolean)
If Not IsPth(Pth) Then Exit Sub
Dim A$
A = RmvSlashAtEnd(Pth)
Fso.DeleteFolder A, Force:=Force
End Sub

Sub PthDlt_Fdr(Pth$)
If Right(Pth, 1) <> "\" Then Err.Raise 1, , "Pth must end with \"
Dim AyFdr$(), J%
AyFdr = PthAyFdr(Pth)
For J = 0 To UB(AyFdr)
    PthDlt Pth & AyFdr(J)
Next
End Sub

Sub PthDlt_File(Pth$)
If Right(Pth, 1) <> "\" Then Err.Raise 1, , "Pth must end with \"
Dim AyFn$(), J%
AyFn = PthAyFn(Pth)
For J = 0 To UB(AyFn)
    FfnDlt Pth & AyFn(J)
Next
End Sub

Function PthCur$()
PthCur = FfnPth(ThisWorkbook.FullName)
End Function
Function PthNorm$(Pth, Optional PthBase$ = "")
'Aim: 'Set' Pth and 'Norm' it
'     Where 'Set'  is If prefix of Pth .\ or ..\, add NonBlank(PthBase,CurDbPth) to Pth
'     Where 'Norm' is to replace \.\ to \ and \..\ to remove 1 lvl in Pth
If Left(Pth, 2) = ".\" Or Left(Pth, 3) = "..\" Then
    If PthBase = "" Then
        Pth = PthCur & Pth
    Else
        Pth = PthBase & Pth
    End If
End If
Pth = Replace(Pth, "\.\", "\")
Dim mP%, mA$, mB$, Brk As S1S2
mP = InStr(Pth, "\..\")
While mP > 0
    Brk = StrBrk(Pth, "\..\")
    mA = Brk.S1
    mB = Brk.S2
    
    Pth = TakBefChrRev(mA, "\", pKeepChr:=True) & mB
    mP = InStr(Pth, "\..\")
Wend
PthNorm = Pth
End Function

Function PthAyFnLik(Pth$, Lik$) As String()
Dim O$(), M$
M = Dir(Pth)
While M <> ""
    If M Like Lik Then Push O, M
    M = Dir
Wend
PthAyFnLik = O
End Function


Sub PthOpn(Pth$)
Shell "explorer """ & Pth & """"
End Sub

Sub PthCrtEachSeg(Pth$)
Dim A$(), iPth$, J%

A = Split(Pth, "\")
iPth = A(0) & "\"
For J = 1 To UB(A) - 1
    iPth = iPth & A(J) & "\"
    If Dir(iPth, vbDirectory) = "" Then MkDir iPth
Next
End Sub

