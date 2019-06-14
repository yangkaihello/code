//
//  BaseFigure.cpp
//  test
//
//  Created by yangkai on 2019/4/8.
//  Copyright © 2019年 hardware. All rights reserved.
//

#include "BaseFigure.hpp"

using namespace std;

namespace cloth {
    
    class BaseFigure
    {
        
    public:
        static const int REFRESH_TRUE = 1;
        static const int REFRESH_FALSE = 2;
        
    protected:
        
        const int x = 29;
        const int y = 17;
        string probability[9] = {"2","2","2","2","2","2","2","4"};
        
        bool refresh=true;
        int xy_x=4;
        int xy_y=4;
        string xy[4][4] = {};
        
        string up_xy[4][4] = {};
        
        void directionFactory(char direction)
        {
            int size = this->size();
            int left;
            int right;
            
            /**
             * 验证是否可以合并
             */
            for (int key=0; key<size; key++) {
                //开始方向->
                left = key;
                //结束方向<-
                right = size-key-1;
                
                switch (direction) {
                    case 'w':
                        if(this->getCoord(left) != "")
                        {
                            this->setCoordTranslationTop(direction,left);
                        }
                        break;
                    case 'a':
                        if(this->getCoord(left) != "")
                        {
                            this->setCoordTranslationLeft(direction,left);
                        }
                        break;
                    case 's':
                        if(this->getCoord(right) != "")
                        {
                            this->setCoordTranslationBottom(direction,right);
                        }
                        break;
                    case 'd':
                        if(this->getCoord(right) != "")
                        {
                            this->setCoordTranslationRight(direction,right);
                        }
                        break;
                }
                
                
            }
            
            for (int key=0; key<size; key++) {
                //开始方向->
                left = key;
                //结束方向<-
                right = size-key-1;
                
                switch (direction) {
                    case 'w':
                        if(this->getCoord(left) == "")
                        {
                            this->setCoordMoveTop(direction,left);
                        }
                        break;
                    case 'a':
                        if(this->getCoord(left) == "")
                        {
                            this->setCoordMoveLeft(direction,left);
                        }
                        break;
                    case 's':
                        if(this->getCoord(right) == "")
                        {
                            this->setCoordMoveBottom(direction,right);
                        }
                        break;
                    case 'd':
                        if(this->getCoord(right) == "")
                        {
                            this->setCoordMoveRight(direction,right);
                        }
                        break;
                }
                
                
            }
            
            
        }
        
        bool setCoordMoveTop(char direction,int key)
        {
            int size = this->size();
            int reset;
            int count = (size-key-1)/4+1;
            
            for (int i=1; i<count; i++) {
                reset = i*4+key;
                
                if(this->getCoord(reset) != "")
                {
                    this->setCoord(key,this->getCoord(reset));
                    this->setCoord(reset,"");
                    break;
                }
            }
            
            return true;
        }
        
        bool setCoordMoveLeft(char direction,int key)
        {
            int reset;
            int count = abs(key%4-4);
            
            for (int i=1; i<count; i++) {
                reset = key+i;
                
                if(this->getCoord(reset) != "")
                {
                    this->setCoord(key,this->getCoord(reset));
                    this->setCoord(reset,"");
                    break;
                }
            }
            
            return true;
        }
        
        bool setCoordMoveBottom(char direction,int key)
        {
            int reset;
            int count = key/4;
            
            for (int i=count; i>0; i--) {
                reset = key-((1+count-i)*4);
                
                if(this->getCoord(reset) != "")
                {
                    this->setCoord(key,this->getCoord(reset));
                    this->setCoord(reset,"");
                    break;
                }
            }
            
            return true;
        }
        
        bool setCoordMoveRight(char direction,int key)
        {
            int reset;
            int count = key%4;
            
            for (int i=1; i<count+1; i++) {
                reset = key-i;
                
                if(this->getCoord(reset) != "")
                {
                    this->setCoord(key,this->getCoord(reset));
                    this->setCoord(reset,"");
                    break;
                }
            }
            
            return true;
        }
        
        bool setCoordTranslationTop(char direction,int key)
        {
            int reset;
            int size = this->size();
            int i;
            int n;
            int setValue;
            bool isMerge = false;
            string value = this->getCoord(key);
            int count = (size-key-1)/4+1;
            
            for (i=1; i<count; i++) {
                reset = i*4+key;
                
                if(this->getCoord(reset) == value)
                {
                    isMerge = true;
                    for (n=1; n<=abs(reset-key)/4-1; n++) {
                        
                        if(this->getCoord(key+(n)*4) != "")
                        {
                            isMerge = false;
                            break;
                        }
                        
                    }
                    break;
                }
            }
            
            if(isMerge)
            {
                setValue = atoi(value.c_str())*2;
                
                this->setCoord(key,to_string(setValue));
                this->setCoord(reset,"");
                isMerge = false;
            }
            
            return true;
        }
        
        bool setCoordTranslationLeft(char direction,int key)
        {
            int reset;
            int i;
            int n;
            int setValue;
            bool isMerge = false;
            string value = this->getCoord(key);
            int count = abs(key%4-4);
            
            for (i=1; i<count; i++) {
                reset = key+i;
                
                if(this->getCoord(reset) == value)
                {
                    isMerge = true;
                    for (n=1; n<=reset-key-1; n++) {
                        
                        if(this->getCoord(key+n) != "")
                        {
                            isMerge = false;
                            break;
                        }
                        
                    }
                    break;
                }
            }
            
            if(isMerge)
            {
                setValue = atoi(value.c_str())*2;
                
                this->setCoord(key,to_string(setValue));
                this->setCoord(reset,"");
                isMerge = true;
            }
            
            return true;
        }
        
        bool setCoordTranslationBottom(char direction,int key)
        {
            int reset;
            int i;
            int n;
            int setValue;
            bool isMerge = false;
            string value = this->getCoord(key);
            
            int count = key/4;
            for (i=count; i>0; i--) {
                reset = key-((1+count-i)*4);
                
                //cout << reset << " " << key << endl;
                if(this->getCoord(reset) == value)
                {
                    isMerge = true;
                    for (n=1; n<=abs(reset-key)/4-1; n++) {
                        
                        if(this->getCoord(key-(n)*4) != "")
                        {
                            isMerge = false;
                            break;
                        }
                    }
                    break;
                }
            }
            
            if(isMerge)
            {
                setValue = atoi(value.c_str())*2;
                
                this->setCoord(key,to_string(setValue));
                this->setCoord(reset,"");
                isMerge = false;
            }
            
            return true;
        }
        
        bool setCoordTranslationRight(char direction,int key)
        {
            int reset;
            int i;
            int n;
            int setValue;
            bool isMerge = false;
            string value = this->getCoord(key);
            int count = key%4;
            
            for (i=1; i<count+1; i++) {
                reset = key-i;
                
                if(this->getCoord(reset) == value)
                {
                    isMerge = true;
                    for (n=1; n<=key-reset-1; n++) {
                        if(this->getCoord(key-n) != "")
                        {
                            isMerge = false;
                            break;
                        }
                    }
                    break;
                }
            }
            
            if(isMerge)
            {
                setValue = atoi(value.c_str())*2;
                
                this->setCoord(key,to_string(setValue));
                this->setCoord(reset,"");
                isMerge = false;
            }
            
            return true;
        }
        
        bool setOne(int start)
        {
            int set = 0;
            int i = 0;
            int x;
            
            while (true) {
                x = this->srand(start+i)%16;
                
                if(this->getCoord(x) == "")
                {
                    this->setCoord(x);
                    set++;
                }else{
                    i++;
                }
                
                if(set == 1)
                {
                    return true;
                }
            }
        }
        
        void setRefresh(int about = BaseFigure::REFRESH_TRUE)
        {
            int size = this->size();
            
            if(about == 1)
            {
                for (int x=0; x<size; x++) {
                    this->setTrueCoord(x,this->getCoord(x));
                }
            }else{
                for (int x=0; x<size; x++) {
                    this->setCoord(x,this->getTrueCoord(x));
                }
            }
            
        }
        
        void setCoord(int inter,string value = "0")
        {
            if(value == "0")
            {
                value = this->probability[this->srand()%8];
            }
            this->up_xy[inter/4%4][inter%4] = value;
        }
        
        void setTrueCoord(int inter,string value = "0")
        {
            if(value == "0")
            {
                value = this->probability[this->srand()%8];
            }
            this->xy[inter/4%4][inter%4] = value;
        }
        
        string getCoord(int inter)
        {
            return this->up_xy[inter/4%4][inter%4];
        }
        
        string getTrueCoord(int inter)
        {
            return this->xy[inter/4%4][inter%4];
        }
        
        int size()
        {
            return sizeof(this->xy) / sizeof(string);
        }
        
        int srand(int x=0)
        {
            std::srand(x+time(0));
            return std::rand();
        }
        
        void printf()
        {
            int size = this->size();
            
            for (int x=0; x<size; x++) {
                cout << this->getTrueCoord(x) << "-";
            }
            cout << endl;
            
            for (int x=0; x<size; x++) {
                cout << this->getCoord(x) << "-";
            }
            cout << endl;
            
        }
        
    };
    
};

