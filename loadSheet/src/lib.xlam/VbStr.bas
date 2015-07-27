Attribute VB_Name = "VbStr"
Option Explicit

Function Fmt(ByVal S$, ParamArray Ap())
Dim Av()
    Av = Ap
    
Dim I, J%, M$
For Each I In Ap
    M = "{" & J & "}"
    J = J + 1
    S = Replace(S, M, I)
Next
Fmt = S
End Function

Function IsSfx(Sfx$, S$) As Boolean
IsSfx = Right(S, Len(Sfx)) = Sfx
End Function

Function IsPfx(Pfx$, S$) As Boolean
IsPfx = Left(S, Len(Pfx)) = Pfx
End Function

Function TakBefChrRev$(P, pChr$, Optional pKeepChr As Boolean = False)
Dim mP%: mP = InStrRev(P, pChr): If mP = 0 Then Exit Function
If pKeepChr Then TakBefChrRev = Left(P, mP - 1 + Len(pChr)): Exit Function
TakBefChrRev = Left(P, mP - 1)
End Function

Function StrSubStrCnt%(Str$, SubStr$)
Dim P%, O%
P = InStr(Str, SubStr)
While P > 0
    O = O + 1
    P = InStr(P + Len(SubStr), Str, SubStr)
Wend
StrSubStrCnt = O
End Function

Function NowStr$()
NowStr = Format(Now(), "YYYY-MM-DD HH:MM:SS")
End Function

Function StrBrk(S, BrkChr$, Optional NoTrim As Boolean = False) As S1S2
Dim mP%, O1$, O2$
mP = InStr(S, BrkChr): If mP = 0 Then Err.Raise 1, "StrBrk", "S[" & S & "] does contains BrkChr[" & BrkChr & "].  Cannot break into 2."
Dim mL%
mL = Len(BrkChr)
If NoTrim Then
    O1 = Left(S, mP - 1)
    O2 = Mid(S, mP + mL)
Else
    O1 = Trim(Left(S, mP - 1))
    O2 = Trim(Mid(S, mP + mL))
End If
StrBrk.S1 = O1
StrBrk.S2 = O2
End Function

Function RmvLastChr$(S$)
RmvLastChr = Left(S, Len(S) - 1)
End Function

Function RmvSlashAtEnd$(Pth$)
If Right(Pth, 1) = "\" Then
    RmvSlashAtEnd = RmvLastChr(Pth)
Else
    RmvSlashAtEnd = Pth
End If
End Function


Sub Pass(PgmNm$)
Debug.Print "Pass: " & PgmNm
End Sub
Function Dft(V$, pDft$)
Dft = IIf(V = "", pDft, V)
End Function

Function EscLF$(ByVal S$)
S = Replace(S, vbCrLf, "\n")
EscLF = Replace(S, vbLf, "\n")
End Function

Function UnEscLF(S$)
UnEscLF = Replace(S, "\n", vbCrLf)
End Function

Function TimStmp()
Static Sno&
TimStmp = Format(Now, "YYYY-MM-DD HHMMSS") & " " & Sno
Sno = Sno + 1
End Function



Function TakAftLastX$(S, X$, Optional AlsoTakX As Boolean = False, Optional ReturnS_IfNoX As Boolean)
Dim P%, L%
P = InStrRev(S, X)
If P = 0 Then
    If ReturnS_IfNoX Then TakAftLastX = S
    Exit Function
End If
If Not AlsoTakX Then L = Len(X)
TakAftLastX = Mid(S, P + L)
End Function


