import 'package:flutter/material.dart';
import 'package:mobile/core/services/storage_service.dart';
import 'package:mobile/features/auth/presentation/pages/login_page.dart';
import 'package:mobile/features/mahasiswa/presentation/pages/dashboard_page.dart';
import 'package:mobile/features/mahasiswa/presentation/pages/profile_page.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  Future<bool> isLoggedIn() async {
    final token = await StorageService.getToken();
    return token != null;
  }

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SIAM',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
      ),
      routes: {
        '/login': (context) => const LoginPage(),
        '/dashboard': (context) => const DashboardPage(),
        '/dosen/dashboard': (context) => const Scaffold(
          body: Center(child: Text("Dashboard Dosen")),
        ),
        '/profile': (context) => const ProfilePage(),
      },
      home: FutureBuilder<bool>(
        future: isLoggedIn(),
        builder: (context, snapshot) {
          // Loading State
          if(!snapshot.hasData) {
            return const Scaffold(
              body: Center(child: CircularProgressIndicator()),
            );
          }
          // Jika sudah login
          if(snapshot.data == true) {
            return const ProfilePage();
          }

          // return const DashboardPage();
          return const LoginPage();
        }
      ),
    );
  }
}