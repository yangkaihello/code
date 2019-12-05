//
//  main.cpp
//  test
//
//  Created by yangkai on 2018/12/8.
//  Copyright © 2018年 hardware. All rights reserved.
//
#include <iostream>
#include <typeinfo>
#include "graphic/cloth.cpp"
using namespace std;

int main (int argc ,char * argv[])
{
    char d;
	string str;
    
    cloth::Sudoku Sudoku;
    Sudoku.start();

    while (true) {
        system("clear");
        
        Sudoku.send();
        
		getline(cin, str);
		d = str[0];
        
        if(d == 'w' || d == 's' || d == 'a' || d == 'd'){
            Sudoku.rand(d);
        }
        
    }
    return 0;
    
}


