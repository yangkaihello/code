//
//  cloth.cpp
//  test
//
//  Created by yangkai on 2019/3/30.
//  Copyright © 2019年 hardware. All rights reserved.
//

#include "cloth.hpp"

using namespace std;

namespace cloth {
    
    class Sudoku : protected BaseFigure
    {
    public:
        string dish()
        {
            string dish;
            int height = y/5+1;
            int width = x/5+2;
            
            for (int y=0; y<this->y; y++) {
                
                for (int x=0; x<this->x; x++) {
                    
                    if((y%height) == 0)
                    {
                        dish += "-";
                    }else{
                        
                        if(x%width == 0)
                        {
                            dish += "|";
                        }else{
                            
                            if(x%width == 1 || x%width == width-1)
                            {
                                dish += " ";
                            }else if(y%height == 2){
                                dish += "#";
                            }else{
                                dish += " ";
                            }
                        }
                        
                    }
                    
                    
                }
                
                dish += "\n";
            }
            
            //        dish = "-----------------------------\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "| 1    | 2048 | 2048 | 2048 |\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "-----------------------------\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "| 2048 | 2048 | 2048 | 2048 |\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "-----------------------------\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "| 2048 | 2048 | 2048 | 2048 |\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "-----------------------------\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "| 2048 | 2048 | 2048 | 2048 |\n";
            //        dish+= "|      |      |      |      |\n";
            //        dish+= "-----------------------------\n";
            
            return dish;
        }
        
        void start()
        {
            for (int i=0; i<2; i++) {
                this->setOne(i);
            }
            this->setRefresh(BaseFigure::REFRESH_TRUE);
        }
        
        void rand(char direction)
        {
            int size = this->size();
            bool isChange = false;
            
            this->directionFactory(direction);
            
            for (int x=0; x<size; x++) {
            
                if(this->getTrueCoord(x) != this->getCoord(x))
                {
                    isChange = true;break;
                }
                
            }
            
            if(isChange)
            {
                this->setOne(this->srand(10));
                this->setRefresh(BaseFigure::REFRESH_TRUE);
            }else{
                
                this->setRefresh(BaseFigure::REFRESH_FALSE);
                
                /**
                 * 验证是否有可用执行方式
                 */
                this->directionFactory('w');
                this->directionFactory('a');
                this->directionFactory('s');
                this->directionFactory('d');
                
                for (int x=0; x<size; x++) {
                    
                    if(this->getTrueCoord(x) != this->getCoord(x))
                    {
                        isChange = true;break;
                    }
                    
                }
                
                this->setRefresh(BaseFigure::REFRESH_FALSE);
            
                if(!isChange)
                {
                    cout << "game over" << endl;
                    exit(0);
                }
            }
            
            
            
        }
        
        
        void send()
        {
            int size = this->size();
            
            size_t index;
            string dish = this->dish();
            string xy;
            
            for (int x=0; x<size; x++) {
                
                xy = this->getTrueCoord(x);
                xy.insert(0,4-xy.length(),' ');
                
                index = dish.find("####");
                dish.replace(index,4,xy);
                
            }
            
            cout << dish << endl;
            
        }
               
    };

};

