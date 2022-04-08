# Server Status - Monitoring
Get the server's ping value and status as up or down
## Screenshot

![Screenshot](https://i.imgur.com/77JtrZl.png)



## Note

If you running Linux server instead of Windows. Linux ping doesn't recognize -n option.

So, you need to update the code on line 146 like this.

```bash
  exec("ping -c 2 ".$server['ip'], $output, $status);
```


## Author

- [@fatihbaskaya](https://www.github.com/fatihbaskaya)