cat saida.txt | awk '{gsub(/\./,"_",$1); print $1}' | awk 'BEGIN{FS="_"}{print $0"," $2}' | sed 's/_png/.png/g'
