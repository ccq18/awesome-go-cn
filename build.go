package main

import (
	"os"
	"io/ioutil"
	"fmt"
	. "strings"
	"container/list"
	//"reflect"
)

//import (
//	"github.com/russross/blackfriday"
//	"github.com/microcosm-cc/bluemonday"
//)
type Type = interface{}

func ReadAll(path string) string {
	fi, err := os.Open(path)
	if err != nil {
		panic(err)
	}
	defer fi.Close()
	fd, err := ioutil.ReadAll(fi)
	// fmt.Println(string(fd))
	return string(fd)
}

func FormartMarkdown(s string, sep string) *list.List {
	s1 := Split(s, "\n")
	now := 0
	hasChild := false
	rs := list.New()
	nowlist := list.New()
	for _, value := range s1 {
		if HasPrefix(s, sep) {
			hasChild = true
			now++
			//fmt.Println("---", value)
			if nowlist.Len() > 0 {
				rs.PushBack(nowlist)
				nowlist = list.New()
			}
			nowlist.PushBack(value)
		} else if hasChild {
			nowlist.PushBack(value)
		} else {
			rs.PushBack(value)
		}

	}

	return rs
}
func main() {

	//html := make([]Type, 5)
	//html[0] = "div"
	//html[1] = "span"
	//html[2] = []byte("script")
	//html[3] = "style"
	//html[4] = "head"
	//for index, element := range html {
	//	switch value := element.(type) {
	//	case string:
	//		fmt.Printf("html[%d] is a string and its value is %s\n", index, value)
	//	case []byte:
	//		fmt.Printf("html[%d] is a []byte and its value is %s\n", index, string(value))
	//	case int:
	//		fmt.Printf("invalid type\n")
	//	default:
	//		fmt.Printf("unknown type\n")
	//	}
	//}

	s := ReadAll("README.md")
	rs := FormartMarkdown(s, "# ")
	for e := rs.Front(); e != nil; e = e.Next() {
		//fmt.Println(reflect.TypeOf(e.Value).String())
		//fmt.Println("111")
		switch  e.Value.(type) {
		case string:
			fmt.Println("|", e.Value, "|")
		case *list.List:
			fmt.Println("|",e.Value.(*list.List).Front().Value, "|")
			for e1 := e.Value.(*list.List).Front(); e1 != nil; e1 = e1.Next() {
				switch value := e1.Value.(type) {
				case string:

					fmt.Println(value)

				}
			}
		}
	}
}
