---
languages_supported:
    - NA
title: PERMUT2
category: NA
old_version: true
problem_code: PERMUT2
tags:
    - NA
layout: problem
---
###  All submissions for this problem are available. 

Some programming contest problems are really tricky: not only do they require a different output format from what you might have expected, but also the sample output does not show the difference. For an example, let us look at permutations.
A **permutation** of the integers _1_ to _n_ is an ordering of these integers. So the natural way to represent a permutation is to list the integers in this order. With _n = 5_, a permutation might look like 2, 3, 4, 5, 1. 
However, there is another possibility of representing a permutation: You create a list of numbers where the _i_-th number is the position of the integer _i_ in the permutation. Let us call this second possibility an **inverse permutation**. The inverse permutation for the sequence above is 5, 1, 2, 3, 4. 
An **ambiguous permutation** is a permutation which cannot be distinguished from its inverse permutation. The permutation 1, 4, 3, 2 for example is ambiguous, because its inverse permutation is the same. To get rid of such annoying sample test cases, you have to write a program which detects if a given permutation is ambiguous or not.

### Input Specification

The input contains several test cases.
The first line of each test case contains an integer _n_(_1 ≤ n ≤ 100000_). Then a permutation of the integers _1_ to _n_ follows in the next line. There is exactly one space character between consecutive integers. You can assume that every integer between _1_ and _n_appears exactly once in the permutation. 
The last test case is followed by a zero.

### Output Specification

For each test case output whether the permutation is ambiguous or not. Adhere to the format shown in the sample output.

### Sample Input

<pre>4
1 4 3 2
5
2 3 4 5 1
1
1
0
</pre>### Sample Output

<pre>ambiguous
not ambiguous
ambiguous
</pre>