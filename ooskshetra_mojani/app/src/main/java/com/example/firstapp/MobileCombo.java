package com.example.firstapp;

    public class MobileCombo
    {
        private Integer id;
        private String name;

        public Integer getId() {
            return id;
        }

        public void setId(Integer id) {
            this.id = id;
        }

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        @Override
        public boolean equals(Object obj)
        {
            if(obj instanceof com.example.firstapp.MobileCombo)
            {
                MobileCombo c = (com.example.firstapp.MobileCombo)obj;
                if(c.getId().equals(id))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

            return false;
        }

        //to display object as a string in spinner
        @Override
        public String toString() {
            return name;
        }
    }

